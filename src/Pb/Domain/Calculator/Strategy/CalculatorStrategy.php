<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\PricingConcept\CollectionFactoryInterface;
use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\ItemFactoryInterface;
use Pb\Domain\PricingConcept\PricingConceptInterface;

abstract class CalculatorStrategy implements PricingConceptInterface
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
     * @return PricingConceptInterface
     */
    public function setItemFactory(ItemFactoryInterface $factory)
    {
        $this->itemFactory = $factory;
        return $this;
    }

    /**
     * @param CollectionFactoryInterface $factory
     * @return PricingConceptInterface
     */
    public function setCollectionFactory(CollectionFactoryInterface $factory)
    {
        $this->collectionFactory = $factory;
        return $this;
    }
}