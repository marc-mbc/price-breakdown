<?php

namespace Pb\Test\Domain\Calculator\Strategy;

use Pb\Test\Domain\Calculator\CalculatorTestHelper;

/**
 * Class CalculatorStrategyTest
 * @package Pb\Test\Domain\Calculator\Strategy
 */
abstract class CalculatorStrategyTest extends CalculatorTestHelper
{

    /**
     * @dataProvider getFactorySetters
     * @param $operation
     * @param $property
     * @param $factory
     */
    public function testSetterFactories($operation, $property, $factory)
    {
        $strategy = $this->getStrategy();
        $this->assertSame($strategy, $strategy->{$operation}($factory));
        $this->assertAttributeSame($factory, $property, $strategy);
    }

    public function getFactorySetters()
    {
        $itemFactory = $this->getItemFactory();
        $collectionFactory = $this->getCollectionFactory();
        return [
            'item_factory' => ['setTaxableItemFactory', 'taxableItemFactory', $itemFactory],
            'collection_factory' => ['setCollectionFactory', 'collectionFactory', $collectionFactory]
        ];
    }

    abstract protected function getStrategy();
}
