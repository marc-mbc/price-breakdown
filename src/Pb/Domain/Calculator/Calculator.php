<?php

namespace Pb\Domain\Calculator;

use Pb\Domain\PriceBreakdown\CollectionFactoryInterface;
use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\ItemFactoryInterface;
use Pb\Domain\PriceBreakdown\CalculatorStrategyInterface;

/**
 * Class Calculator
 * @package Pb\Domain\Calculator
 */
class Calculator implements CalculatorInterface
{
    /**
     * @var CalculatorStrategyInterface[]
     */
    protected $PriceBreakdowns = [];
    /**
     * @var CollectionFactoryInterface
     */
    protected $collectionFactory;
    /**
     * @var ItemFactoryInterface
     */
    protected $itemFactory;

    /**
     * Calculator constructor.
     * @param CollectionFactoryInterface $collectionFactory
     * @param ItemFactoryInterface $itemFactory
     */
    public function __construct(CollectionFactoryInterface $collectionFactory, ItemFactoryInterface $itemFactory)
    {
        $this->collectionFactory = $collectionFactory;
        $this->itemFactory = $itemFactory;
    }

    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function calculate(CollectionInterface $collection)
    {
        foreach($this->PriceBreakdowns as $PriceBreakdown)
        {
            $collection = $PriceBreakdown->apply($collection);
        }
        return $collection;
    }

    /**
     * @param CalculatorStrategyInterface $PriceBreakdown
     * @param CollectionFactoryInterface $collectionFactory
     * @param ItemFactoryInterface $itemFactory
     * @return CalculatorInterface
     */
    public function addPriceBreakdown(
        CalculatorStrategyInterface $PriceBreakdown,
        CollectionFactoryInterface $collectionFactory = null,
        ItemFactoryInterface $itemFactory = null
    )
    {
        $PriceBreakdown->setCollectionFactory(
            $collectionFactory === null ? $this->collectionFactory : $collectionFactory
        );
        $PriceBreakdown->setItemFactory($itemFactory === null ? $this->itemFactory : $itemFactory);
        $this->PriceBreakdowns[] = $PriceBreakdown;
        return $this;
    }
}