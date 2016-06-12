<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\PriceBreakdown\CollectionFactoryInterface;
use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\ItemFactoryInterface;
use Pb\Domain\PriceBreakdown\CalculatorStrategyInterface;

abstract class CalculatorStrategy implements CalculatorStrategyInterface
{
    /**
     * @var ItemFactoryInterface
     */
    protected $itemFactory;
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
     * @param ItemFactoryInterface $factory
     * @return CalculatorStrategyInterface
     */
    public function setItemFactory(ItemFactoryInterface $factory)
    {
        $this->itemFactory = $factory;
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