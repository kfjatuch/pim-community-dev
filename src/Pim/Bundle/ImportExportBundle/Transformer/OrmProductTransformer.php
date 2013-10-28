<?php

namespace Pim\Bundle\ImportExportBundle\Transformer;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Doctrine\ORM\Query;
use Oro\Bundle\BatchBundle\Item\InvalidItemException;
use Pim\Bundle\ImportExportBundle\Cache\AttributeCache;
use Pim\Bundle\CatalogBundle\Manager\ProductManager;
use Pim\Bundle\CatalogBundle\Model\ProductInterface;
use Pim\Bundle\ImportExportBundle\Validator\Import\ProductImportValidator;
use Pim\Bundle\ImportExportBundle\Exception\InvalidValueException;

/**
 * Transforms a CSV product in an entity
 * 
 * @author    Antoine Guigan <antoine@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class OrmProductTransformer
{
    /**
     * @var ProductManager
     */
    protected $productManager;

    /**
     * @var AttributeCache
     */
    protected $attributeCache;

    /**
     * @var ProductImportValidator
     */
    protected $productValidator;

    /**
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor;

    /**
     * @var array
     */
    protected $propertyTransformers;

    /**
     * @var array
     */
    protected $attributeTransformers;

    /**
     * @var Query
     */
    private $importQuery;
            
    public function __construct(
        ProductManager $productManager,
        ProductImportValidator $productValidator,
        AttributeCache $attributeCache,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->productManager = $productManager;
        $this->productValidator = $productValidator;
        $this->attributeCache = $attributeCache;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function getProduct(array $values, array $mapping = array(), array $defaults = array())
    {
        
        $this->mapValues($values, $mapping);
        $attributeValues = array_diff_key($values, $this->propertyTransformers);
        $propertyValues = array_intersect_key($values, $this->propertyTransformers);
        if (!$this->attributeCache->isInitialized()) {
            $this->attributeCache->initialize(array_keys($attributeValues));
        }

        $product = $this->createOrLoadProduct($values, $defaults);
        
        $this->setDefaultValues($product, $defaults);
        $errors = array_merge(
            $this->setPropertyValues($product, $propertyValues),
            $this->setAttributeValues($product, $attributeValues)
        );
        if (count($errors)) {
            throw new InvalidItemException(implode("\n", $errors), $values);
        }
        return $product;
    }

    public function addPropertyTransformer(
        $propertyPath,
        Property\PropertyTransformerInterface $transformer,
        array $options=array()
    ) {
        $this->propertyTransformers[$propertyPath] = array(
            'transformer' => $transformer,
            'options'     => $options
        );
    }

    public function addAttributeTransformer(
        $backendType,
        Property\PropertyTransformerInterface $transformer,
        array $options=array()
    ) {
        $this->attributeTransformers[$backendType] = array(
            'transformer' => $transformer,
            'options'     => $options
        );
    }

    protected function mapValues(&$values, array $mapping)
    {
        foreach ($mapping as $oldName => $newName) {
            if ($oldName != $newName && isset($values[$oldName])) {
                $values[$newName] = $values[$oldName];
                unset($values[$oldName]);
            }
        }
    }

    protected function createOrLoadProduct(array $values, array $defaults)
    {
        if (!isset($this->importQuery)) {
            $this->importQuery = $this->productManager->getImportQuery(
                $this->attributeCache->getAttributes(), 
                $this->attributeCache->getIdentifierAttribute()
            );
        }
        $this->importQuery->setParameter('code', $values[$this->attributeCache->getIdentifierAttribute()->getCode()]);
        $product = $this->importQuery->getOneOrNullResult();

        if (!$product) {
            $product = $this->productManager->createProduct();
        }

        foreach($defaults as $propertyPath=>$value) {
            $this->propertyAccessor->setValue($product, $propertyPath, $value);
        }

        return $product;
    }

    protected function setPropertyValues(ProductInterface $product, array $values)
    {
        $errors = array();

        foreach ($values as $propertyPath => $value) {
            try {
                $this->propertyAccessor->setValue(
                    $product,
                    $propertyPath,
                    $this->getTransformedValue($this->propertyTransformers[$propertyPath], $value)
                );
            } catch (InvalidValueException $ex) {
                $errors[] = $this->productValidator->getTranslatedExceptionMessage($propertyPath, $ex);
            }
        }

        return array_merge(
            $errors,
            $this->productValidator->validateProductProperties($product, $values)
        );
    }
    protected function setDefaultValues(ProductInterface $product, $defaults)
    {
        foreach ($defaults as $propertyPath => $value) {
            $this->propertyAccessor->setValue($product, $propertyPath, $value);
        }
    }
    protected function setAttributeValues(ProductInterface $product, array $attributeValues)
    {
        $requiredAttributeCodes = $this->getRequiredAttributeCodes($product);
        $columns = $this->attributeCache->getColumns();
        $errors = array();
        foreach ($attributeValues as $columnCode=>$columnValue) {
            $columnInfo = $columns[$columnCode];
            try  {
                if ($columnValue || in_array($columnInfo['code'], $requiredAttributeCodes)) {
                    $errors = array_merge(
                        $errors,
                        $this->productValidator->validateProductValue(
                            $columnCode,
                            $columnInfo['attribute'],
                            $columnValue
                        )
                    );
                }
            } catch (InvalidValueException $ex) {
                $errors[] = $this->productValidator->getTranslatedExceptionMessage($columnCode, $ex);
            }
        }

        return $errors;
    }
    protected function setAttributeValue(ProductInterface $product, $value, array $columnInfo) {
        $productValue = $product->getValue($columnInfo['code'], $columnInfo['locale'], $columnInfo['scope']);
        if (!$productValue) {
            $productValue = $product->createValue($columnInfo['code'], $columnInfo['locale'], $columnInfo['scope']);
            $product->addValue($productValue);
        }
        $productValue->setData($value);
    }
    protected function getAttributeValue($value, array $columnInfo) {
        $backendType = $columnInfo['attribute']->getBackendType();
        if ($value) {
            if (isset($this->attributeTransformers[$backendType])) {
                $value = $this->getTransformedValue($this->attributeTransformers[$backendType], $value);
            }
        } else {
            $value = null;
        }
        return $value;
    }
    protected function getTransformedValue(array $transformerConfig, $value)
    {
        return $transformerConfig['transformer']->transform($value, $transformerConfig['options']);
    }

    protected function getRequiredAttributeCodes(ProductInterface $product)
    {
        $requiredAttributes = array();

        if ($product->getFamily()) {
            $requiredAttributes = $product->getFamily()->getAttributes()->toArray();
        }

        foreach($product->getGroups() as $group) {
            $requiredAttributes = array_merge($requiredAttributes, $group->getAttributes()->toArray());
        }

        if ($product->getId()) {
            foreach ($product->getValues() as $value) {
                $requiredAttributes[] = $value->getAttribute();
            }
        }

        return array_unique(
            array_map(
                function ($attribute) {
                    return $attribute->getCode();
                },
                $requiredAttributes
            )
        );
    }
}
