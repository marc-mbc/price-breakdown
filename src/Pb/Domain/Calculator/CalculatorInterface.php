<?php

namespace Pb\Domain\Calculator;

use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\PricingConceptInterface;

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
     * @param PricingConceptInterface $concept
     * @return CalculatorInterface
     */
    public function addPricingConcept(PricingConceptInterface $concept);
}