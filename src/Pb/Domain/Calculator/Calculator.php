<?php

namespace Pb\Domain\Calculator;

use Pb\Domain\Calculator\Strategy\CalculatorStrategy;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Interface Calculator
 * @package Pb\Domain\Calculator
 */
interface Calculator
{
    /**
     * @param TaxableCollection $initialTaxableCollection
     * @return TaxableCollection
     */
    public function calculate(TaxableCollection $initialTaxableCollection);

    /**
     * @param CalculatorStrategy $strategy
     * @param TaxableCollectionFactory $taxableCollectionFactory
     * @param TaxableItemFactory $itemFactory
     * @return Calculator
     */
    public function addStrategy(
        CalculatorStrategy $strategy,
        TaxableCollectionFactory $taxableCollectionFactory = null,
        TaxableItemFactory $itemFactory = null
    );
}