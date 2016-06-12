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
     * @return Calculator
     */
    protected function getCalculator()
    {
        return new Calculator();
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
     * @param string $type
     * @param Money $gross
     * @return CollectionInterface
     */
    protected function getCollectionWithSingleItem(
        CollectionFactoryInterface $taxableCollectionFactory,
        ItemFactoryInterface $taxableItemFactory,
        $currencyCode,
        $type,
        Money $gross
    )
    {
        $expectedCollection = $taxableCollectionFactory->build($this->getCurrency($currencyCode));
        $expectedCollection->add(
            $type,
            $taxableItemFactory->buildWithGross($gross)
        );
        return $expectedCollection;
    }
}