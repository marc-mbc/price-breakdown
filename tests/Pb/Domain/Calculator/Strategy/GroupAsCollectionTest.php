<?php

namespace Pb\Test\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\Strategy\CalculatorStrategy;
use Pb\Domain\Calculator\Strategy\GroupAsCollection;

/**
 * Class GroupAsCollectionTest
 * @package Pb\Test\Domain\Calculator\Strategy
 */
class GroupAsCollectionTest extends CalculatorStrategyTest
{
    public function testStrategyWorksAsExpected()
    {
        $collectionFactory = $this->getTaxableCollectionFactory();
        $taxableItemFactory = $this->getTaxableItemFactory();
        $conceptName = 'basePrice';
        $currencyCode = 'EUR';
        $gross = $this->getMoney(120.24, $currencyCode);

        $simpleCollection = $this->getCollectionWithSingleItem(
            $collectionFactory,
            $taxableItemFactory,
            $currencyCode,
            $conceptName,
            $gross
        );

        $strategy = $this->getStrategy($conceptName);
        $strategy->setTaxableCollectionFactory($collectionFactory);
        $strategy->setTaxableItemFactory($taxableItemFactory);

        $this->assertEquals(
            $simpleCollection,
            $strategy->apply(
                $simpleCollection
            )->find($conceptName)
        );
    }

    /**
     * @param string $conceptName
     * @return CalculatorStrategy
     */
    protected function getStrategy($conceptName = 'default')
    {
        return new GroupAsCollection($conceptName);
    }
}
