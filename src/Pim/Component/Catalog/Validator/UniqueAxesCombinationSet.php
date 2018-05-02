<?php

declare(strict_types=1);

namespace Pim\Component\Catalog\Validator;

use Pim\Component\Catalog\Exception\AlreadyExistingAxisValueCombinationException;
use Pim\Component\Catalog\Model\EntityWithFamilyVariantInterface;
use Pim\Component\Catalog\Model\ProductInterface;

/**
 * Contains the state of the unique axis values combination for an entity with family variant.
 * We use this state to deal with bulk update and validation.
 *
 * @author    Damien Carcel <damien.carcel@gmail.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UniqueAxesCombinationSet
{
    /** @var array */
    private $uniqueAxesCombination;

    /**
     * Initializes the set.
     */
    public function __construct()
    {
        $this->uniqueAxesCombination = [];
    }

    /**
     * Resets the set.
     */
    public function reset(): void
    {
        $this->uniqueAxesCombination = [];
    }

    /**
     * Adds a new axis value combination. If it already exists, throw an
     * exception with the code/identifier of the entity that already contains
     * this combination.
     *
     * @param EntityWithFamilyVariantInterface $entity
     * @param string                           $axesCombination
     *
     * @throws AlreadyExistingAxisValueCombinationException
     */
    public function addCombination(EntityWithFamilyVariantInterface $entity, string $axesCombination): void
    {
        $familyVariantCode = $entity->getFamilyVariant()->getCode();
        $parentCode = $entity->getParent()->getCode();
        $identifier = $this->getEntityIdentifier($entity);

        if (isset($this->uniqueAxesCombination[$familyVariantCode][$parentCode][$axesCombination])) {
            $cachedIdentifier = $this->uniqueAxesCombination[$familyVariantCode][$parentCode][$axesCombination];
            if ($cachedIdentifier !== $identifier) {
                throw new AlreadyExistingAxisValueCombinationException(
                    $cachedIdentifier,
                    get_class($entity),
                    $axesCombination
                );
            }
        }

        if (!isset($this->uniqueAxesCombination[$familyVariantCode])) {
            $this->uniqueAxesCombination[$familyVariantCode] = [];
        }

        if (!isset($this->uniqueAxesCombination[$familyVariantCode][$parentCode])) {
            $this->uniqueAxesCombination[$familyVariantCode][$parentCode] = [];
        }

        if (!isset($this->uniqueAxesCombination[$familyVariantCode][$parentCode][$axesCombination])) {
            $this->uniqueAxesCombination[$familyVariantCode][$parentCode][$axesCombination] = $identifier;
        }
    }

    /**
     * @param EntityWithFamilyVariantInterface $entity
     *
     * @return string
     */
    private function getEntityIdentifier(EntityWithFamilyVariantInterface $entity): string
    {
        if ($entity instanceof ProductInterface) {
            return $entity->getIdentifier();
        }

        return $entity->getCode();
    }
}
