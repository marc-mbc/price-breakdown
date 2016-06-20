<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\PriceBreakdown\CollectionFactoryInterface;
use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\CalculatorStrategyInterface;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Class CalculatorStrategy
 * @package Pb\Domain\Calculator\Strategy
 */
abstract class CalculatorStrategy implements CalculatorStrategyInterface
{
    /**
     * @var TaxableItemFactory
     */
    protected $taxableItemFactory;
    /**
     * @var CollectionFactoryInterface
     */
    protected $collectionFactory;

    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    abstract public function apply(CollectionInterface $collection);

    /**
     * @param TaxableItemFactory $factory
     * @return CalculatorStrategyInterface
     */
    public function setTaxableItemFactory(TaxableItemFactory $factory)
    {
        $this->taxableItemFactory = $factory;
        return $this;
    }

    /**
     * @param CollectionFactoryInterface $factory
     * @return CalculatorStrategyInterface
     */
    public function setCollectionFactory(CollectionFactoryInterface $factory)
    {
        $this->collectionFactory = $factory;
        return $this;
    }
}