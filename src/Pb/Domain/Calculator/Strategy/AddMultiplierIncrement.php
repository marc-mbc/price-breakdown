<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\PricingConcept\CollectionInterface;

/**
 * Class AddMultiplierIncrement
 * @package Pb\Domain\Calculator\Strategy
 */
class AddMultiplierIncrement extends CalculatorStrategy
{
    /**
     * @var MultiplierInterface
     */
    protected $multiplier;
    /**
     * @var string
     */
    protected $conceptName;

    /**
     * AddAmount constructor.
     * @param string $conceptName
     * @param MultiplierInterface $multiplier
     */
    public function __construct($conceptName, MultiplierInterface $multiplier)
    {
        $this->conceptName = $conceptName;
        $this->multiplier = $multiplier;
    }
    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function apply(CollectionInterface $collection)
    {
        return $collection->add(
            $this->conceptName,
            $this->itemFactory->buildWithGross($collection->gross()->multiply($this->multiplier->multiplier()))
        );
    }
}