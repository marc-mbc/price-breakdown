<?php

namespace Pb\Domain\PriceBreakdown\TaxableCollection;

use Money\Currency;
use Pb\Domain\PriceBreakdown\Taxable;

/**
 * Class TaxableCollectionFactory
 * @package Pb\Domain\PriceBreakdown\TaxableCollection
 */
class TaxableCollectionFactory
{

    /**
     * @param Currency $currency
     * @param Taxable|null $aggregate
     * @param array $items
     * @return TaxableCollection
     */
    public function build(Currency $currency, $aggregate = null, array $items = [])
    {
        return new TaxableCollection($currency, $aggregate, $items);
    }
}