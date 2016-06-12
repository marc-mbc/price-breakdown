<?php

namespace Pb\Domain\Calculator;

use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\PricingConceptInterface;

/**
 * Class Calculator
 * @package Pb\Domain\Calculator
 */
class Calculator implements CalculatorInterface
{
    /**
     * @var PricingConceptInterface[]
     */
    protected $pricingConcepts = [];

    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function calculate(CollectionInterface $collection)
    {
        foreach($this->pricingConcepts as $pricingConcept)
        {
            $collection = $pricingConcept->apply($collection);
        }
        return $collection;
    }

    /**
     * @param PricingConceptInterface $pricingConcept
     * @return CalculatorInterface
     */
    public function addPricingConcept(PricingConceptInterface $pricingConcept)
    {
        $this->pricingConcepts[] = $pricingConcept;
        return $this;
    }
}