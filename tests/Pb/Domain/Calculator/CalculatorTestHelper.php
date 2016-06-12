<?php

namespace Pb\Test\Domain\Calculator;

use Money\Money;
use Pb\Domain\Calculator\Calculator;
use Pb\Domain\PriceBreakdown\CollectionFactoryInterface;
use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\ItemFactoryInterface;
use Pb\Test\Domain\PriceBreakdown\PriceBreakdownTestHelper;

/**
 * Class CalculatorTestHelper
 * @package Pb\Test\Domain\Calculator
 */
abstract class CalculatorTestHelper extends PriceBreakdownTestHelper
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