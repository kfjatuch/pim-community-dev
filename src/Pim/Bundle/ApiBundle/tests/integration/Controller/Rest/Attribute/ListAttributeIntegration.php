<?php

namespace tests\integration\Pim\Bundle\ApiBundle\Controller\Rest\Attribute;

use Akeneo\Test\Integration\Configuration;
use Pim\Bundle\ApiBundle\tests\integration\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class ListAttributeIntegration extends ApiTestCase
{
    public function testListAttributes()
    {
        $client = $this->createAuthentifiedClient();

        $client->request('GET', 'api/rest/v1/attributes');

        $standardAttributes = [
            0 => [
                'code'                   => 'sku',
                'type'                   => 'pim_catalog_identifier',
                'group'                  => 'attributeGroupA',
                'unique'                 => true,
                'useable_as_grid_filter' => true,
                'allowed_extensions'     => [],
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => false,
                'negative_allowed'       => false,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            1 => [
                'code'                   => 'a_date',
                'type'                   => 'pim_catalog_date',
                'group'                  => 'attributeGroupA',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => false,
                'negative_allowed'       => false,
                'date_min'               => '2005-05-25T00:00:00+02:00',
                'date_max'               => '2050-12-31T00:00:00+01:00',
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 2,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            2 => [
                'code'                   => 'a_file',
                'type'                   => 'pim_catalog_file',
                'group'                  => 'attributeGroupA',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [
                    0 => 'pdf',
                    1 => 'doc',
                    2 => 'docx',
                ],
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => false,
                'negative_allowed'       => false,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 1,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            3 => [
                'code'                   => 'an_image',
                'type'                   => 'pim_catalog_image',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [
                    0 => 'jpg',
                    1 => 'gif',
                    2 => 'png',
                ],
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => false,
                'negative_allowed'       => false,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => '500.00',
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            4 => [
                'code'                   => 'a_metric',
                'type'                   => 'pim_catalog_metric',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => 'Power',
                'default_metric_unit'    => 'KILOWATT',
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => true,
                'negative_allowed'       => false,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            5 => [
                'code'                   => 'a_metric_without_decimal',
                'type'                   => 'pim_catalog_metric',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => 'Length',
                'default_metric_unit'    => 'METER',
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => false,
                'negative_allowed'       => false,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            6 => [
                'code'                   => 'a_metric_without_decimal_negative',
                'type'                   => 'pim_catalog_metric',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => 'Temperature',
                'default_metric_unit'    => 'CELSIUS',
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => false,
                'negative_allowed'       => true,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            7 => [
                'code'                   => 'a_metric_negative',
                'type'                   => 'pim_catalog_metric',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => 'Temperature',
                'default_metric_unit'    => 'CELSIUS',
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => true,
                'negative_allowed'       => true,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            8 => [
                'code'                   => 'a_multi_select',
                'type'                   => 'pim_catalog_multiselect',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => false,
                'negative_allowed'       => false,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            9 => [
                'code'                   => 'a_number_float',
                'type'                   => 'pim_catalog_number',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => true,
                'negative_allowed'       => false,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
        ];

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame($standardAttributes, json_decode($response->getContent(), true));
    }

    public function testListAttributesWithLimitAndPage()
    {
        $client = $this->createAuthentifiedClient();

        $client->request('GET', 'api/rest/v1/attributes?limit=5&page=2');
        $standardAttributes = [
            0 => [
                'code'                   => 'a_metric_without_decimal',
                'type'                   => 'pim_catalog_metric',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => 'Length',
                'default_metric_unit'    => 'METER',
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => false,
                'negative_allowed'       => false,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            1 => [
                'code'                   => 'a_metric_without_decimal_negative',
                'type'                   => 'pim_catalog_metric',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => 'Temperature',
                'default_metric_unit'    => 'CELSIUS',
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => false,
                'negative_allowed'       => true,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            2 => [
                'code'                   => 'a_metric_negative',
                'type'                   => 'pim_catalog_metric',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => 'Temperature',
                'default_metric_unit'    => 'CELSIUS',
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => true,
                'negative_allowed'       => true,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            3 => [
                'code'                   => 'a_multi_select',
                'type'                   => 'pim_catalog_multiselect',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => false,
                'negative_allowed'       => false,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
            4 => [
                'code'                   => 'a_number_float',
                'type'                   => 'pim_catalog_number',
                'group'                  => 'attributeGroupB',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [],
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'wysiwyg_enabled'        => false,
                'number_min'             => null,
                'number_max'             => null,
                'decimals_allowed'       => true,
                'negative_allowed'       => false,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => 0,
                'sort_order'             => 0,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [],
            ],
        ];
        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame($standardAttributes, json_decode($response->getContent(), true));
    }

    public function testOutOfRangeListAttributes()
    {
        $client = $this->createAuthentifiedClient();

        $client->request('GET', 'api/rest/v1/attributes?limit=100&page=2');

        $standardAttributes = [];

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame($standardAttributes, json_decode($response->getContent(), true));
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration(
            [Configuration::getTechnicalCatalogPath()],
            false
        );
    }
}
