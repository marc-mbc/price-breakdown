<?php
namespace Pb\Test\Domain\PricingConcept\ValueObject;

use Pb\Domain\PricingConcept\ValueObject\TaxableItem;
use Pb\Test\Domain\PricingConcept\PricingConceptTestHelper;
use Money\Money;

/**
 * Class TaxableItemTest
 * @package Pb\Test\Domain\PricingConcept\ValueObject
 */
class TaxableItemTest extends PricingConceptTestHelper
{
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

    public function testNet()
    {
        $net = $this->getMoney(12.5);
        $vat = $this->getMoney(2.5);
        $item = new TaxableItem($net, $vat);
        $this->assertSame($net, $item->net());
    }

    public function testVat()
    {
        $net = $this->getMoney(12.5);
        $vat = $this->getMoney(2.5);
        $item = new TaxableItem($net, $vat);
        $this->assertSame($vat, $item->vat());
    }
}
