<?php

namespace Pb\Domain\PriceBreakdown;

use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Interface CalculatorStrategyInterface
 * @package Pb\Domain\PriceBreakdown
 */
interface CalculatorStrategyInterface
{
    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function apply(CollectionInterface $collection);

    /**
     * @param TaxableItemFactory $factory
     * @return CalculatorStrategyInterface
     */
    public function setTaxableItemFactory(TaxableItemFactory $factory);

    /**
     * @param CollectionFactoryInterface $factory
     * @return CalculatorStrategyInterface
     */
    public function setCollectionFactory(CollectionFactoryInterface $factory);
}