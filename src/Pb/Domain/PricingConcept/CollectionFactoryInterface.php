<?php

namespace Pb\Domain\PricingConcept;

use Money\Currency;

/**
 * Interface CollectionFactoryInterface
 * @package Pb\Domain\PricingConcept
 */
interface CollectionFactoryInterface
{
    /**
     * @param Currency $currency
     * @param ItemInterface|null $aggregate
     * @param array $items
     * @return CollectionInterface
     */
    public function build(Currency $currency, $aggregate = null, array $items = []);
}