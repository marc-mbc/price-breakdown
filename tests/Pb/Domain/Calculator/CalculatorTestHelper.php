<?php

namespace Pb\Test\Domain\Calculator;

use Money\Money;
use Pb\Domain\Calculator\BasicCalculator;
use Pb\Domain\Calculator\Calculator;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;
use Pb\Test\Domain\PriceBreakdown\PriceBreakdownTestHelper;

/**
 * Class CalculatorTestHelper
 * @package Pb\Test\Domain\Calculator
 */
abstract class CalculatorTestHelper extends PriceBreakdownTestHelper
{
    /**
     * @param TaxableCollectionFactory $collectionFactory
     * @param TaxableItemFactory $itemFactory
     * @return Calculator
     */
    protected function getCalculator(
        TaxableCollectionFactory $collectionFactory = null, TaxableItemFactory $itemFactory = null
    )
    {
        return new BasicCalculator(
            $collectionFactory === null ? $this->getTaxableCollectionFactory() : $collectionFactory,
            $itemFactory === null ? $this->getTaxableItemFactory() : $itemFactory
        );
    }

    /**
     * @param $currencyCode
     * @return TaxableCollection
     */
    protected function getEmptyCollection($currencyCode = 'EUR')
    {
        return $this->getTaxableCollectionFactory()->build($this->getCurrency($currencyCode));
    }

    /**
     * @param TaxableCollectionFactory $taxableCollectionFactory
     * @param TaxableItemFactory $taxableItemFactory
     * @param string $currencyCode
     * @param string $conceptName
     * @param Money $gross
     * @return TaxableCollection
     */
    protected function getCollectionWithSingleItem(
        TaxableCollectionFactory $taxableCollectionFactory,
        TaxableItemFactory $taxableItemFactory,
        $currencyCode,
        $conceptName,
        Money $gross
    )
    {
        $expectedCollection = $taxableCollectionFactory->build($this->getCurrency($currencyCode));
        $expectedCollection->addUp(
            $conceptName,
            $taxableItemFactory->buildWithGross($gross)
        );
        return $expectedCollection;
    }
}