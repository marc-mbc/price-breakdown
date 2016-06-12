<?php

namespace Pb\Domain\PricingConcept\Collection;

use Money\Currency;
use Pb\Domain\PricingConcept\CollectionFactoryInterface;
use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\ItemInterface;

/**
 * Class TaxableCollectionFactory
 * @package Pb\Domain\PricingConcept\Collection
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