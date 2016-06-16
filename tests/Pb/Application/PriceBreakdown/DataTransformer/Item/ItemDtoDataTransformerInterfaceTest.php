<?php

namespace Pb\Test\Application\PriceBreakdown\DataTransformer;

use Pb\Application\PriceBreakdown\DataTransformer\ItemDtoDataTransformer;
use Pb\Domain\PriceBreakdown\ItemInterface;
use Pb\Test\Domain\PriceBreakdown\PriceBreakdownTestHelper;

/**
 * Class ItemDtoDataTransformerTest
 * @package Pb\Test\Application\PriceBreakdown\DataTransformer
 */
class ItemDtoDataTransformerTest extends PriceBreakdownTestHelper
{
    const TRANSFORM_TO_DTO = 'transformToDto';
    const TRANSFORM_TO_DOMAIN = 'transformToDomain';

    /**
     * @dataProvider getTransformToDtoCases
     * @param string $operation
     * @param array|ItemInterface $expected
     * @param array|ItemInterface $source
     */
    public function testTransformToDto($operation, $expected, $source)
    {
        $dataTransformer = $this->getDataTransformer();

        $this->assertEquals($expected, $dataTransformer->{$operation}($source));
    }

    public function getSerializationCases()
    {
        $currencyCode = 'EUR';
        $net = 100.25;
        $vat = 20.25;
        $gross = 120.5;

        $arrayItem = $this->getArrayItem($currencyCode, $net, $vat, $gross);
        $item = $this->getItemFactory()->buildFromBasicTypes($currencyCode, $net, $vat, $gross);

        return [
            'simple_case_transform_to_dto' => [
                static::TRANSFORM_TO_DTO,
                $arrayItem,
                $item
            ],
            'simple_case_transform_to_domain' => [
                static::TRANSFORM_TO_DOMAIN,
                $item,
                $arrayItem,
            ]
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getInvalidDtoCases
     * @param array $source
     */
    public function testInvalidSerializationCases(array $source)
    {
        $dataTransformer = $this->getDataTransformer();

        $dataTransformer->transformToDomain($source);
    }

    public function getInvalidDtoCases()
    {
        $currencyCode = 'EUR';
        $net = 100.25;
        $vat = 20.25;
        $gross = 120.5;

        return [
            'empty_item' => [
                [],
            ],
            'item_without_gross' => [
                $this->removeKeyFromItem(
                    $this->getArrayItem($currencyCode, $net, $vat, $gross), ItemDtoDataTransformer::GROSS
                ),
            ],
            'item_without_net' => [
                $this->removeKeyFromItem(
                    $this->getArrayItem($currencyCode, $net, $vat, $gross), ItemDtoDataTransformer::NET
                ),
            ],
            'item_without_vat' => [
                $this->removeKeyFromItem(
                    $this->getArrayItem($currencyCode, $net, $vat, $gross), ItemDtoDataTransformer::VAT
                ),
            ],
            'item_without_currency' => [
                $this->removeKeyFromItem(
                    $this->getArrayItem($currencyCode, $net, $vat, $gross), ItemDtoDataTransformer::CURRENCY
                ),
            ]
        ];
    }

    /**
     * @return ItemDtoDataTransformer
     */
    protected function getDataTransformer()
    {
        return new ItemDtoDataTransformer($this->getItemFactory(), $this->getMoneyFormatter());
    }

    /**
     * @param string $currencyCode
     * @param float|int $net
     * @param float|int $vat
     * @param float|int $gross
     * @return array
     */
    protected function getArrayItem($currencyCode, $net, $vat, $gross)
    {
        return [
            ItemDtoDataTransformer::CURRENCY => $currencyCode,
            ItemDtoDataTransformer::NET => $net,
            ItemDtoDataTransformer::VAT => $vat,
            ItemDtoDataTransformer::GROSS => $gross
        ];
    }

    /**
     * @param array $item
     * @param $key
     * @return array
     */
    protected function removeKeyFromItem(array $item, $key)
    {
        unset($item[$key]);
        return $item;
    }
}
