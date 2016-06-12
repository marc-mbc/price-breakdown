<?php

namespace Pb\Test\Domain\Calculator;

use Money\Money;
use Pb\Domain\Calculator\Calculator;
use Pb\Domain\PricingConcept\CollectionFactoryInterface;
use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\ItemFactoryInterface;
use Pb\Test\Domain\PricingConcept\PricingConceptTestHelper;

/**
 * Class CalculatorTestHelper
 * @package Pb\Test\Domain\Calculator
 */
abstract class CalculatorTestHelper extends PricingConceptTestHelper
{
    /**
     * @param CollectionFactoryInterface $collectionFactory
     * @param ItemFactoryInterface $itemFactory
     * @return Calculator
     */
    protected function getCalculator(
        CollectionFactoryInterface $collectionFactory = null, ItemFactoryInterface $itemFactory = null
    )
    {
        return new Calculator(
            $collectionFactory === null ? $this->getCollectionFactory() : $collectionFactory,
            $itemFactory === null ? $this->getItemFactory() : $itemFactory
        );
    }

    /**
     * @param $currencyCode
     * @return CollectionInterface
     */
    protected function getEmptyCollection($currencyCode = 'EUR')
    {
        return $this->getCollectionFactory()->build($this->getCurrency($currencyCode));
    }

    /**
     * @param CollectionFactoryInterface $taxableCollectionFactory
     * @param ItemFactoryInterface $taxableItemFactory
     * @param string $currencyCode
     * @param string $conceptName
     * @param Money $gross
     * @return CollectionInterface
     */
    protected function getCollectionWithSingleItem(
        CollectionFactoryInterface $taxableCollectionFactory,
        ItemFactoryInterface $taxableItemFactory,
        $currencyCode,
        $conceptName,
        Money $gross
    )
    {
        $expectedCollection = $taxableCollectionFactory->build($this->getCurrency($currencyCode));
        $expectedCollection->add(
            $conceptName,
            $taxableItemFactory->buildWithGross($gross)
        );
        return $expectedCollection;
    }
}