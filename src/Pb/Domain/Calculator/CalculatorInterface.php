<?php

namespace Pb\Domain\Calculator;

use Pb\Domain\PriceBreakdown\CollectionFactoryInterface;
use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\CalculatorStrategyInterface;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

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
     * @param CalculatorStrategyInterface $strategy
     * @param CollectionFactoryInterface $collectionFactory
     * @param TaxableItemFactory $itemFactory
     * @return CalculatorInterface
     */
    public function addStrategy(
        CalculatorStrategyInterface $strategy,
        CollectionFactoryInterface $collectionFactory = null,
        TaxableItemFactory $itemFactory = null
    );
}