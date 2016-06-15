<?php

namespace Pb\Test\Domain\PriceBreakdown\ValueObject;

use Money\Money;
use Pb\Test\Domain\PriceBreakdown\PriceBreakdownTestHelper;

/**
 * Class TaxableItemFactoryTest
 * @package Pb\Test\Domain\PriceBreakdown\ValueObject
 */
class TaxableItemFactoryTest extends PriceBreakdownTestHelper
{
    const CONSTRUCTED_CLASS = 'Pb\Domain\PriceBreakdown\ValueObject\TaxableItem';

    /**
     * @dataProvider getBuildFromBasicTypesCases
     * @param string $currency
     * @param float $net
     * @param float $vat
     * @param float|null $gross
     */
    public function testBuildFromBasicTypesShouldReturnProperClass ($currency, $net, $vat, $gross)
    {
        $this->assertInstanceOf(
            self::CONSTRUCTED_CLASS,
            $this->getItemFactory()->buildFromBasicTypes($currency, $net, $vat, $gross)
        );
    }

    /**
     * @dataProvider getBuildFromBasicTypesCases
     * @param string $currency
     * @param float $net
     * @param float $vat
     * @param float|null $gross
     */
    public function testBuildFromBasicTypesShouldReturnItemProperlySetUp ($currency, $net, $vat, $gross)
    {
        $item = $this->getItemFactory()->buildFromBasicTypes($currency, $net, $vat, $gross);
        $this->assertEquals($this->getMoney($net, $currency), $item->net());
        $this->assertEquals($this->getMoney($vat, $currency), $item->vat());
        if ($gross !== null) $this->assertEquals($this->getMoney($gross, $currency), $item->gross());
    }

    /**
     * @dataProvider getBuildCases
     * @param Money $net
     * @param Money $vat
     * @param Money $gross
     */
    public function testBuildShouldReturnProperClass ($net, $vat, $gross)
    {
        $this->assertInstanceOf(
            self::CONSTRUCTED_CLASS,
            $this->getItemFactory()->build($net, $vat, $gross)
        );
    }

    /**
     * @dataProvider getBuildCases
     * @param Money $net
     * @param Money $vat
     * @param Money $gross
     */
    public function testBuildShouldReturnItemProperlySetUp ($net, $vat, $gross)
    {
        $item = $this->getItemFactory()->build($net, $vat, $gross);
        $this->assertEquals($net, $item->net());
        $this->assertEquals($vat, $item->vat());
        if ($gross !== null) $this->assertEquals($gross, $item->gross());
    }

    public function testBuildFromGrossShouldReturnProperClass ()
    {
        $this->assertInstanceOf(
            self::CONSTRUCTED_CLASS,
            $this->getItemFactory()->buildWithGross($this->getMoney(100))
        );
    }

    /**
     * @dataProvider getBuildWithGrossCases
     * @param float $vatToApply
     * @param Money $gross
     */
    public function testBuildFromGrossShouldReturnItemProperlySetUp($vatToApply, $gross)
    {
        $item = $this->getItemFactory($this->getTaxApplicator($vatToApply))->buildWithGross($gross);
        $net = $gross->divide(1 + $vatToApply);
        $this->assertEquals($net, $item->net(), 'Net');
        $this->assertEquals($gross->subtract($net), $item->vat(), 'Vat');
        $this->assertEquals($gross, $item->gross(), 'Gross');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Vat to apply must be [0-1] float
     * @dataProvider getBuildWithGrossInvalidCases
     * @param float $vatToApply
     * @param Money $gross
     */
    public function testBuildFromGrossShouldReturnExceptionWithInvalidTaxToApply($vatToApply, $gross)
    {
        $this->getItemFactory($this->getTaxApplicator($vatToApply))->buildWithGross($gross);
    }

    public function getBuildWithGrossInvalidCases()
    {
        $gross = $this->getMoney(100);
        return [
            'with_vat_to_apply_lower_than_0' => [-0.99, $gross],
            'with_vat_to_apply_higher_than_1' => [1.01, $gross],
            'string_as_vat_to_apply' => ['invalid', $gross],
            'out_of_range_numeric_string_as_vat_to_apply' => ['1.1', $gross],
        ];
    }

    public function getBuildWithGrossCases()
    {
        $gross = $this->getMoney(100);
        return [
            'with_vat_to_apply' => [0.20, $gross],
            'without_vat_to_apply' => [0, $gross],
        ];
    }

    public function getBuildCases()
    {
        $currency = 'EUR';
        return [
            'without_gross' => [
                $this->getMoney(212.32, $currency),
                $this->getMoney(34.32, $currency),
                null
            ],
            'with_gross' => [
                $this->getMoney(212.32, $currency),
                $this->getMoney(34.32, $currency),
                $this->getMoney(334.32, $currency)
            ]
        ];
    }

    public function getBuildFromBasicTypesCases()
    {
        return [
            'without_gross' => ['EUR', 121.89, 2.67, null],
            'with_gross' => ['EUR', 121.89, 2.67, 124]
        ];
    }
}
