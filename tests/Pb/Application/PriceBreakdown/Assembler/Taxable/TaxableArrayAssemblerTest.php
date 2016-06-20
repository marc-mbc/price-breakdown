<?php

namespace Pb\Test\Application\PriceBreakdown\Assembler\Taxable;

use Pb\Application\PriceBreakdown\Assembler\Taxable\TaxableArrayAssembler;
use Pb\Domain\PriceBreakdown\Taxable;
use Pb\Test\Domain\PriceBreakdown\PriceBreakdownTestHelper;

/**
 * Class TaxableArrayAssemblerTest
 * @package Pb\Test\Application\PriceBreakdown\Assembler\Taxable
 */
class TaxableArrayAssemblerTest extends PriceBreakdownTestHelper
{
    const ASSEMBLE_OPERATION = 'assemble';
    const DISASSEMBLE_OPERATION = 'disassemble';

    /**
     * @dataProvider getAssemblerValidCases
     * @param string $operation
     * @param array|Taxable $expected
     * @param array|Taxable $source
     */
    public function testAssembler($operation, $expected, $source)
    {
        $dataTransformer = $this->getAssembler();

        $this->assertEquals($expected, $dataTransformer->{$operation}($source));
    }

    public function getAssemblerValidCases()
    {
        $currencyCode = 'EUR';
        $net = 100.25;
        $vat = 20.25;
        $gross = 120.5;

        $arrayTaxableItem = $this->getArrayItem($currencyCode, $net, $vat, $gross);
        $taxableItem = $this->getTaxableItemFactory()->buildFromBasicTypes($currencyCode, $net, $vat, $gross);

        return [
            'simple_case_transform_to_dto' => [
                static::ASSEMBLE_OPERATION,
                $arrayTaxableItem,
                $taxableItem
            ],
            'simple_case_transform_to_domain' => [
                static::DISASSEMBLE_OPERATION,
                $taxableItem,
                $arrayTaxableItem,
            ]
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getInvalidCases
     * @param array $source
     */
    public function testAssemblerInvalidCases(array $source)
    {
        $dataTransformer = $this->getAssembler();

        $dataTransformer->{static::DISASSEMBLE_OPERATION}($source);
    }

    public function getInvalidCases()
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
                    $this->getArrayItem($currencyCode, $net, $vat, $gross), TaxableArrayAssembler::GROSS
                ),
            ],
            'item_without_net' => [
                $this->removeKeyFromItem(
                    $this->getArrayItem($currencyCode, $net, $vat, $gross), TaxableArrayAssembler::NET
                ),
            ],
            'item_without_vat' => [
                $this->removeKeyFromItem(
                    $this->getArrayItem($currencyCode, $net, $vat, $gross), TaxableArrayAssembler::VAT
                ),
            ],
            'item_without_currency' => [
                $this->removeKeyFromItem(
                    $this->getArrayItem($currencyCode, $net, $vat, $gross), TaxableArrayAssembler::CURRENCY
                ),
            ]
        ];
    }

    /**
     * @return TaxableArrayAssembler
     */
    protected function getAssembler()
    {
        return new TaxableArrayAssembler($this->getTaxableItemFactory(), $this->getMoneyFormatter());
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
            TaxableArrayAssembler::CURRENCY => $currencyCode,
            TaxableArrayAssembler::NET => $net,
            TaxableArrayAssembler::VAT => $vat,
            TaxableArrayAssembler::GROSS => $gross
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
