<?php

namespace Pb\Domain\Calculator;

use Pb\Domain\PriceBreakdown\CalculatorStrategyInterface;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Interface CalculatorInterface
 * @package Pb\Domain\Calculator
 */
interface CalculatorInterface
{
    /**
     * @param TaxableCollection $initialTaxableCollection
     * @return TaxableCollection
     */
    public function calculate(TaxableCollection $initialTaxableCollection);

    /**
     * @param CalculatorStrategyInterface $strategy
     * @param TaxableCollectionFactory $taxableCollectionFactory
     * @param TaxableItemFactory $itemFactory
     * @return CalculatorInterface
     */
    public function addStrategy(
        CalculatorStrategyInterface $strategy,
        TaxableCollectionFactory $taxableCollectionFactory = null,
        TaxableItemFactory $itemFactory = null
    );
}