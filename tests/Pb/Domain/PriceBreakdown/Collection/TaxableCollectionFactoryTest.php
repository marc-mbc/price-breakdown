<?php

namespace Pb\Test\Domain\PriceBreakdown\Collection;

use Money\Currency;
use Pb\Domain\PriceBreakdown\ItemInterface;
use Pb\Test\Domain\PriceBreakdown\PriceBreakdownTestHelper;

/**
 * Class TaxableCollectionFactoryTest
 * @package Pb\Test\Domain\PriceBreakdown\Collection
 */
class TaxableCollectionFactoryTest extends PriceBreakdownTestHelper
{
    const CONSTRUCTED_CLASS = 'Pb\Domain\PriceBreakdown\Collection\TaxableCollection';

    /**
     * @dataProvider getBuildCases
     * @param Currency $currency
     * @param ItemInterface $aggregate
     * @param array $items
     */
    public function testBuildShouldReturnProperClass (Currency $currency, ItemInterface $aggregate = null, array $items)
    {
        $this->assertInstanceOf(
            self::CONSTRUCTED_CLASS,
            $this->getCollectionFactory()->build($currency, $aggregate, $items)
        );
    }

    public function getBuildCases()
    {
        $code = 'EUR';
        $currency = new Currency($code);
        return [
            'only_currency' => [$currency, null, []],
            'with_currency_aggregate' => [$currency, $this->getItemFactory()->buildFromBasicTypes($code, 121.89, 2.67, 124), []],
            'with_currency_aggregate_items' => [$currency, $this->getItemFactory()->buildFromBasicTypes($code, 121.89, 2.67, 124), [
                $this->getItemFactory()->buildFromBasicTypes($code, 121.89, 2.67, 124),
                $this->getItemFactory()->buildFromBasicTypes($code, 121.89, 2.67, 128),
            ]]
        ];
    }
}
