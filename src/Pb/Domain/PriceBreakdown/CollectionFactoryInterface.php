<?php

namespace Pb\Domain\PriceBreakdown;

use Money\Currency;

/**
 * Interface CollectionFactoryInterface
 * @package Pb\Domain\PriceBreakdown
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