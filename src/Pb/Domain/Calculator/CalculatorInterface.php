<?php

namespace Pb\Domain\Calculator;

use Pb\Domain\PriceBreakdown\CollectionFactoryInterface;
use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\ItemFactoryInterface;
use Pb\Domain\PriceBreakdown\CalculatorStrategyInterface;

/**
 * Interface CalculatorInterface
 * @package Pb\Domain\Calculator
 */
interface CalculatorInterface
{
    /**
     * @param CollectionInterface $initialCollection
     * @return CollectionInterface
     */
    public function calculate(CollectionInterface $initialCollection);

    /**
     * @param CalculatorStrategyInterface $concept
     * @param CollectionFactoryInterface $collectionFactory
     * @param ItemFactoryInterface $itemFactory
     * @return CalculatorInterface
     */
    public function addPriceBreakdown(
        CalculatorStrategyInterface $concept,
        CollectionFactoryInterface $collectionFactory = null,
        ItemFactoryInterface $itemFactory = null
    );
}