<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\Calculator;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;

/**
 * Class AddCalculationFromCalculator
 * @package Pb\Domain\Calculator\Strategy
 */
class AddCalculationFromCalculator extends CalculatorStrategy
{
    /**
     * @var Calculator
     */
    protected $calculator;

    /**
     * AddCalculationFromCalculator constructor.
     * @param Calculator $calculator
     */
    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @param TaxableCollection $taxableCollection
     * @return TaxableCollection
     */
    public function apply(TaxableCollection $taxableCollection)
    {
        return $this->calculator->calculate($taxableCollection);
    }
}