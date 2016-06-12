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
        $type = 'basePrice';
        $currencyCode = 'EUR';
        $gross = $this->getMoney(120.24, $currencyCode);

        $simpleCollection = $this->getCollectionWithSingleItem(
            $collectionFactory,
            $itemFactory,
            $currencyCode,
            $type,
            $gross
        );

        $this->assertEquals(
            $simpleCollection,
            $this->getStrategy($collectionFactory, $type)->apply(
                $simpleCollection
            )->find($type)
        );
    }

    /**
     * @param CollectionFactoryInterface $factory
     * @param string $type
     * @return PricingConceptInterface
     */
    protected function getStrategy(CollectionFactoryInterface $factory, $type)
    {
        return new AddTypeOnNewRootCollection($factory, $type);
    }
}
