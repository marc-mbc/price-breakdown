<?php

namespace Pb\Infrastructure\PriceBreakdown\Collection;

use Money\Currency;
use Pb\Domain\PriceBreakdown\Collection\TaxableCollection;
use Pb\Domain\PriceBreakdown\CollectionFactoryInterface;
use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\ItemInterface;

/**
 * Class TaxableCollectionFactory
 * @package Pb\Domain\PriceBreakdown\Collection
 */
class TaxableCollectionFactory implements CollectionFactoryInterface
{

    /**
     * @param Currency $currency
     * @param ItemInterface|null $aggregate
     * @param array $items
     * @return CollectionInterface
     */
    public function build(Currency $currency, $aggregate = null, array $items = [])
    {
        return new TaxableCollection($currency, $aggregate, $items);
    }
}