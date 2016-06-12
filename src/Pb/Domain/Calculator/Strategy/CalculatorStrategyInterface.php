<?php

namespace Pb\Domain\PriceBreakdown;

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
     * @param ItemFactoryInterface $factory
     * @return CalculatorStrategyInterface
     */
    public function setItemFactory(ItemFactoryInterface $factory);

    /**
     * @param CollectionFactoryInterface $factory
     * @return CalculatorStrategyInterface
     */
    public function setCollectionFactory(CollectionFactoryInterface $factory);
}