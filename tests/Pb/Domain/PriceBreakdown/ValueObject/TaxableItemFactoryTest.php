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

    public function getBuildFromBasicTypesCases()
    {
        return [
            'without_gross' => ['EUR', 121.89, 2.67, null],
            'with_gross' => ['EUR', 121.89, 2.67, 124]
        ];
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

    /**
     * @dataProvider getBuildWithGrossCases
     * @param float $taxToApply
     */
    public function testBuildFromGrossShouldReturnItemProperlySetUp($taxToApply)
    {
        $gross = $this->getMoney(100);
        $item = $this->getItemFactory($this->getTaxApplicator($taxToApply))->buildWithGross($gross);

        $expectedNet = $this->getTaxApplicator($taxToApply)->netFromGross($gross);
        $expectedVat = $gross->subtract($expectedNet);
        $expectedGross = $gross;

        $this->assertEquals($expectedNet, $item->net(), 'Net');
        $this->assertEquals($expectedVat, $item->vat(), 'Vat');
        $this->assertEquals($expectedGross, $item->gross(), 'Gross');
    }

    public function getBuildWithGrossCases()
    {
        return [
            'with_vat_to_apply' => [0.20],
            'without_vat_to_apply' => [0],
        ];
    }
}
