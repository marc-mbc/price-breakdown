<?php

namespace Pb\Domain\Calculator;

use Pb\Domain\PriceBreakdown\CollectionFactoryInterface;
use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\CalculatorStrategyInterface;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Class Calculator
 * @package Pb\Domain\Calculator
 */
class Calculator implements CalculatorInterface
{
    /**
     * @var CalculatorStrategyInterface[]
     */
    protected $strategies = [];
    /**
     * @var CollectionFactoryInterface
     */
    protected $collectionFactory;
    /**
     * @var TaxableItemFactory
     */
    protected $taxableItemFactory;

    /**
     * Calculator constructor.
     * @param CollectionFactoryInterface $collectionFactory
     * @param TaxableItemFactory $itemFactory
     */
    public function __construct(CollectionFactoryInterface $collectionFactory, TaxableItemFactory $itemFactory)
    {
        $this->collectionFactory = $collectionFactory;
        $this->taxableItemFactory = $itemFactory;
    }

    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function calculate(CollectionInterface $collection)
    {
        foreach($this->strategies as $strategy)
        {
            $collection = $strategy->apply($collection);
        }
        return $collection;
    }

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
    )
    {
        $strategy->setCollectionFactory(
            $collectionFactory === null ? $this->collectionFactory : $collectionFactory
        );
        $strategy->setTaxableItemFactory($itemFactory === null ? $this->taxableItemFactory : $itemFactory);
        $this->strategies[] = $strategy;
        return $this;
    }
}