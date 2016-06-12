<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\CalculatorInterface;
use Pb\Domain\PriceBreakdown\CollectionInterface;

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
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function apply(CollectionInterface $collection)
    {
        return $this->calculator->calculate($collection);
    }
}