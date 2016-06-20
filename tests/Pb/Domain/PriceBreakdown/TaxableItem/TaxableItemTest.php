<?php
namespace Pb\Test\Domain\PriceBreakdown\TaxableItem;

use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItem;
use Pb\Test\Domain\PriceBreakdown\PriceBreakdownTestHelper;
use Money\Money;

/**
 * Class TaxableItemTest
 * @package Pb\Test\Domain\PriceBreakdown\Taxable
 */
class TaxableItemTest extends PriceBreakdownTestHelper
{
    public function testConstructShouldProperlySetNet()
    {
        $net = $this->getMoney(12.5);
        $vat = $this->getMoney(2.5);
        $item = new TaxableItem($net, $vat);
        $this->assertSame($net, $item->net());
    }

    public function testConstructShouldProperlySetVat()
    {
        $net = $this->getMoney(12.5);
        $vat = $this->getMoney(2.5);
        $item = new TaxableItem($net, $vat);
        $this->assertSame($vat, $item->vat());
    }

    public function testConstructWithoutGrossShouldCreateGross()
    {
        $net = $this->getMoney(12.5);
        $vat = $this->getMoney(2.5);
        $item = new TaxableItem($net, $vat);
        $this->assertEquals($net->add($vat), $item->gross());
    }

    public function testConstructWithGrossShouldUseSameGross()
    {
        $net = $this->getMoney(12.5);
        $vat = $this->getMoney(2.5);
        $gross = $net->add($vat);
        $item = new TaxableItem($net, $vat, $gross);
        $this->assertSame($gross, $item->gross());
    }

    /**
     * @dataProvider getComparisonCases
     * @param bool $expected
     * @param TaxableItem $taxableItemA
     * @param TaxableItem $taxableItemB
     */
    public function testTaxableItemEquals($expected, TaxableItem $taxableItemA, TaxableItem $taxableItemB)
    {
        $this->assertEquals($expected, $taxableItemA->equals($taxableItemB));
    }

    public function getComparisonCases()
    {
        $taxableItemFactory = $this->getTaxableItemFactory();
        $item = $taxableItemFactory->buildFromBasicTypes('EUR', 100, 20, 120);

        return [
            'same_instance' => [true, $item, $item],
            'different_instance_same_properties' => [
                true,
                $taxableItemFactory->buildFromBasicTypes('EUR', 100, 20, 120),
                $taxableItemFactory->buildFromBasicTypes('EUR', 100, 20, 120)
            ],
            'different_currency' => [
                false,
                $taxableItemFactory->buildFromBasicTypes('EUR', 100, 20, 120),
                $taxableItemFactory->buildFromBasicTypes('USD', 100, 20, 120)
            ],
            'different_net' => [
                false,
                $taxableItemFactory->buildFromBasicTypes('EUR', 100, 20, 120),
                $taxableItemFactory->buildFromBasicTypes('EUR', 101, 20, 120)
            ],
            'different_vat' => [
                false,
                $taxableItemFactory->buildFromBasicTypes('EUR', 100, 21, 120),
                $taxableItemFactory->buildFromBasicTypes('EUR', 100, 20, 120)
            ],
            'different_gross' => [
                false,
                $taxableItemFactory->buildFromBasicTypes('EUR', 100, 20, 120),
                $taxableItemFactory->buildFromBasicTypes('EUR', 100, 20, 121)
            ],
            'all_different' => [
                false,
                $taxableItemFactory->buildFromBasicTypes('EUR', 100, 22, 122),
                $taxableItemFactory->buildFromBasicTypes('USD', 101, 20, 121)
            ],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage All prices must be in the same Currency
     * @dataProvider getInvalidConstructionsDifferentCurrencies
     * @param Money $net
     * @param Money $vat
     * @param Money|null $gross
     */
    public function testConstructDifferentCurrenciesShouldThrowInvalidArgumentException($net, $vat, $gross)
    {
        new TaxableItem($net, $vat, $gross);
    }

    public function getInvalidConstructionsDifferentCurrencies()
    {
        return [
            'without_gross' => [$this->getMoney(12.5, 'EUR'), $this->getMoney(2.5, 'USD'), null],
            'with_gross_different_from_net' => [$this->getMoney(12.5, 'EUR'), $this->getMoney(2.5, 'USD'), $this->getMoney(15, 'USD')],
            'with_gross_same_as_net' => [$this->getMoney(12.5, 'EUR'), $this->getMoney(2.5, 'USD'), $this->getMoney(15, 'EUR')]
        ];
    }
}
