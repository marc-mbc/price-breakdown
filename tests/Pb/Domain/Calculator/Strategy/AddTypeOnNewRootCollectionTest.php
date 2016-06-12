<?php

namespace Pb\Test\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\Strategy\AddTypeOnNewRootCollection;
use Pb\Domain\PricingConcept\PricingConceptInterface;

/**
 * Class AddTypeOnNewRootCollectionTest
 * @package Pb\Domain\Calculator\Strategy
 */
class AddTypeOnNewRootCollectionTest extends CalculatorStrategyTest
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

        $strategy = $this->getStrategy($conceptName);
        $strategy->setCollectionFactory($collectionFactory);
        $strategy->setItemFactory($itemFactory);
        $this->assertEquals(
            $simpleCollection,
            $strategy->apply(
                $simpleCollection
            )->find($conceptName)
        );
    }

    /**
     * @param string $conceptName
     * @return PricingConceptInterface
     */
    protected function getStrategy($conceptName = 'default')
    {
        return new AddTypeOnNewRootCollection($conceptName);
    }
}
