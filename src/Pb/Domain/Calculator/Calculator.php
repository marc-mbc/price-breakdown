<?php

namespace Pb\Domain\Calculator;

use Pb\Domain\PricingConcept\CollectionFactoryInterface;
use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\ItemFactoryInterface;
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
        foreach($this->pricingConcepts as $pricingConcept)
        {
            $collection = $pricingConcept->apply($collection);
        }
        return $collection;
    }

    /**
     * @param PricingConceptInterface $pricingConcept
     * @param CollectionFactoryInterface $collectionFactory
     * @param ItemFactoryInterface $itemFactory
     * @return CalculatorInterface
     */
    public function addPricingConcept(
        PricingConceptInterface $pricingConcept,
        CollectionFactoryInterface $collectionFactory = null,
        ItemFactoryInterface $itemFactory = null
    )
    {
        $pricingConcept->setCollectionFactory(
            $collectionFactory === null ? $this->collectionFactory : $collectionFactory
        );
        $pricingConcept->setItemFactory($itemFactory === null ? $this->itemFactory : $itemFactory);
        $this->pricingConcepts[] = $pricingConcept;
        return $this;
    }
}