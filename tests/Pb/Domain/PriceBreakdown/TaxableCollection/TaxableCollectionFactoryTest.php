<?php

namespace Pb\Test\Domain\PriceBreakdown\Collection;

use Money\Currency;
use Pb\Domain\PriceBreakdown\Taxable;
use Pb\Test\Domain\PriceBreakdown\PriceBreakdownTestHelper;

/**
 * Class TaxableCollectionFactoryTest
 * @package Pb\Test\Domain\PriceBreakdown\Collection
 */
class TaxableCollectionFactoryTest extends PriceBreakdownTestHelper
{
    const CONSTRUCTED_CLASS = 'Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection';

    /**
     * @dataProvider getBuildCases
     * @param Currency $currency
     * @param Taxable $aggregate
     * @param array $items
     */
    public function testBuildShouldReturnProperClass (Currency $currency, Taxable $aggregate = null, array $items)
    {
        $this->assertInstanceOf(
            self::CONSTRUCTED_CLASS,
            $this->getTaxableCollectionFactory()->build($currency, $aggregate, $items)
        );
    }

    public function getBuildCases()
    {
        $code = 'EUR';
        $currency = new Currency($code);
        return [
            'only_currency' => [$currency, null, []],
            'with_currency_aggregate' => [$currency, $this->getTaxableItemFactory()->buildFromBasicTypes($code, 121.89, 2.67, 124), []],
            'with_currency_aggregate_items' => [$currency, $this->getTaxableItemFactory()->buildFromBasicTypes($code, 121.89, 2.67, 124), [
                $this->getTaxableItemFactory()->buildFromBasicTypes($code, 121.89, 2.67, 124),
                $this->getTaxableItemFactory()->buildFromBasicTypes($code, 121.89, 2.67, 128),
            ]]
        ];
    }
}
