<?php

namespace Pb\Test\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\Strategy\GroupAsCollection;
use Pb\Domain\PriceBreakdown\CalculatorStrategyInterface;

/**
 * Class GroupAsCollectionTest
 * @package Pb\Domain\Calculator\Strategy
 */
class GroupAsCollectionTest extends CalculatorStrategyTest
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
     * @return CalculatorStrategyInterface
     */
    protected function getStrategy($conceptName = 'default')
    {
        return new GroupAsCollection($conceptName);
    }
}
