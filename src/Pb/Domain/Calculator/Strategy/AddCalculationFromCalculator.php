<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\CalculatorInterface;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;

/**
 * Class AddCalculationFromCalculator
 * @package Pb\Domain\Calculator\Strategy
 */
class AddCalculationFromCalculator extends CalculatorStrategy
{
    /**
     * @var CalculatorInterface
     */
    protected $calculator;

    /**
     * AddCalculationFromCalculator constructor.
     * @param CalculatorInterface $calculator
     */
    public function __construct(CalculatorInterface $calculator)
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