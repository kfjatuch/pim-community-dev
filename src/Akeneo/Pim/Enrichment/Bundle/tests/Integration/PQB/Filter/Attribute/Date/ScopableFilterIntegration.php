<?php

namespace Akeneo\Pim\Enrichment\Bundle\tests\Integration\PQB\Filter\Date;

use Akeneo\Pim\Enrichment\Bundle\tests\Integration\PQB\AbstractProductQueryBuilderTestCase;
use Akeneo\Pim\Structure\Component\AttributeTypes;
use Akeneo\Pim\Enrichment\Component\Product\Query\Filter\Operators;

/**
 * @author    Marie Bochu <marie.bochu@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ScopableFilterIntegration extends AbstractProductQueryBuilderTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->createAttribute([
            'code'                => 'a_scopable_date',
            'type'                => AttributeTypes::DATE,
            'localizable'         => false,
            'scopable'            => true
        ]);

        $this->createProduct('product_one', [
            'values' => [
                'a_scopable_date' => [
                    ['data' => '2016-04-23', 'scope' => 'ecommerce', 'locale' => null],
                    ['data' => '2016-04-23', 'scope' => 'tablet', 'locale' => null],
                ]
            ]
        ]);

        $this->createProduct('product_two', [
            'values' => [
                'a_scopable_date' => [
                    ['data' => '2016-09-23', 'scope' => 'ecommerce', 'locale' => null],
                ]
            ]
        ]);

        $this->createProduct('empty_product', []);
    }

    public function testOperatorInferior()
    {
        $result = $this->executeFilter([['a_scopable_date', Operators::LOWER_THAN, '2016-04-23', ['scope' => 'ecommerce']]]);
        $this->assert($result, []);

        $result = $this->executeFilter([['a_scopable_date', Operators::LOWER_THAN, '2016-09-24', ['scope' => 'ecommerce']]]);
        $this->assert($result, ['product_one', 'product_two']);
    }

    public function testOperatorEquals()
    {
        $result = $this->executeFilter([['a_scopable_date', Operators::EQUALS, '2016-09-23', ['scope' => 'ecommerce']]]);
        $this->assert($result, ['product_two']);

        $result = $this->executeFilter([['a_scopable_date', Operators::EQUALS, '2016-04-23', ['scope' => 'ecommerce']]]);
        $this->assert($result, ['product_one']);

        $result = $this->executeFilter([['a_scopable_date', Operators::EQUALS, '2016-07-23', ['scope' => 'ecommerce']]]);
        $this->assert($result, []);
    }

    public function testOperatorSuperior()
    {
        $result = $this->executeFilter([['a_scopable_date', Operators::GREATER_THAN, '2016-09-23', ['scope' => 'ecommerce']]]);
        $this->assert($result, []);

        $result = $this->executeFilter([['a_scopable_date', Operators::GREATER_THAN, '2016-09-22', ['scope' => 'ecommerce']]]);
        $this->assert($result, ['product_two']);
    }

    public function testOperatorEmpty()
    {
        $result = $this->executeFilter([['a_scopable_date', Operators::IS_EMPTY, [], ['scope' => 'ecommerce']]]);
        $this->assert($result, ['empty_product']);

        $result = $this->executeFilter([['a_scopable_date', Operators::IS_EMPTY, [], ['scope' => 'tablet']]]);
        $this->assert($result, ['product_two', 'empty_product']);
    }

    public function testOperatorNotEmpty()
    {
        $result = $this->executeFilter([['a_scopable_date', Operators::IS_NOT_EMPTY, [], ['scope' => 'ecommerce']]]);
        $this->assert($result, ['product_one', 'product_two']);
    }

    public function testOperatorDifferent()
    {
        $result = $this->executeFilter([['a_scopable_date', Operators::NOT_EQUAL, '2016-09-23', ['scope' => 'ecommerce']]]);
        $this->assert($result, ['product_one']);
    }

    public function testOperatorBetween()
    {
        $result = $this->executeFilter([['a_scopable_date', Operators::BETWEEN, ['2016-09-23', '2016-09-23'], ['scope' => 'ecommerce']]]);
        $this->assert($result, ['product_two']);

        $result = $this->executeFilter([['a_scopable_date', Operators::BETWEEN, ['2016-04-23', '2016-09-23'], ['scope' => 'ecommerce']]]);
        $this->assert($result, ['product_one', 'product_two']);
    }

    public function testOperatorNotBetween()
    {
        $result = $this->executeFilter([['a_scopable_date', Operators::NOT_BETWEEN, ['2016-09-23', '2016-09-23'], ['scope' => 'ecommerce']]]);
        $this->assert($result, ['product_one']);

        $result = $this->executeFilter([['a_scopable_date', Operators::NOT_BETWEEN, [
            new \DateTime('2016-04-23T00:00:00'), '2016-09-23'
        ], ['scope' => 'ecommerce']]]);
        $this->assert($result, []);
    }

    /**
     * @expectedException \Akeneo\Tool\Component\StorageUtils\Exception\InvalidPropertyException
     * @expectedExceptionMessage Attribute "a_scopable_date" expects a scope, none given.
     */
    public function testErrorMetricScopable()
    {
        $this->executeFilter([['a_scopable_date', Operators::NOT_EQUAL, '2016-09-23']]);
    }

    /**
     * @expectedException \Akeneo\Tool\Component\StorageUtils\Exception\InvalidPropertyException
     * @expectedExceptionMessage Attribute "a_scopable_date" expects an existing scope, "NOT_FOUND" given.
     */
    public function testScopeNotFound()
    {
        $this->executeFilter([['a_scopable_date', Operators::NOT_EQUAL, '2016-09-23', ['scope' => 'NOT_FOUND']]]);
    }
}
