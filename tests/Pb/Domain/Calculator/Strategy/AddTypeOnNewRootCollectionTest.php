<?php

namespace Pb\Test\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\Strategy\AddTypeOnNewRootCollection;
use Pb\Domain\PricingConcept\CollectionFactoryInterface;
use Pb\Domain\PricingConcept\PricingConceptInterface;
use Pb\Test\Domain\Calculator\CalculatorTestHelper;

/**
 * Class AddTypeOnNewRootCollectionTest
 * @package Pb\Domain\Calculator\Strategy
 */
class AddTypeOnNewRootCollectionTest extends CalculatorTestHelper
{
    public function testStrategyWorksAsExpected()
    {
        $collectionFactory = $this->getCollectionFactory();
        $itemFactory = $this->getItemFactory();
        $conceptName = 'basePrice';
        $currencyCode = 'EUR';
        $gross = $this->getMoney(120.24, $currencyCode);

        $simpleCollection = $this->getCollectionWithSingleItem(
            $collectionFactory,
            $itemFactory,
            $currencyCode,
            $conceptName,
            $gross
        );

        $this->assertEquals(
            $simpleCollection,
            $this->getStrategy($collectionFactory, $conceptName)->apply(
                $simpleCollection
            )->find($conceptName)
        );
    }

    /**
     * @param CollectionFactoryInterface $factory
     * @param string $conceptName
     * @return PricingConceptInterface
     */
    protected function getStrategy(CollectionFactoryInterface $factory, $conceptName)
    {
        return new AddTypeOnNewRootCollection($factory, $conceptName);
    }
}
