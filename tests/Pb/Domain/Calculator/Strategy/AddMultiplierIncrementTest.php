<?php

namespace Pb\Test\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\Strategy\AddMultiplierIncrement;
use Pb\Domain\Calculator\Strategy\Multiplier;
use Pb\Domain\Calculator\Strategy\MultiplierInterface;
use Pb\Domain\PricingConcept\ItemFactoryInterface;
use Pb\Domain\PricingConcept\PricingConceptInterface;
use Pb\Test\Domain\Calculator\CalculatorTestHelper;

class AddMultiplierIncrementTest extends CalculatorTestHelper
{
    public function testStrategyWorksAsExpected()
    {
        $taxableItemFactory = $this->getItemFactory();
        $taxableCollectionFactory = $this->getCollectionFactory();
        $type = 'basePrice';
        $multiplierType = 'extraFee';
        $currencyCode = 'EUR';
        $gross = $this->getMoney(120.24, $currencyCode);
        $multiplier = 0.5;
        $multiplierStrategy = $this->getMultiplier($multiplier);


        $expectedCollection = $this->getCollectionWithSingleItem(
            $taxableCollectionFactory,
            $taxableItemFactory,
            $currencyCode,
            $type,
            $gross
        );
        $expectedCollection->add(
            $multiplierType,
            $taxableItemFactory->buildWithGross($gross->multiply($multiplierStrategy->multiplier()))
        );

        $this->assertEquals(
            $expectedCollection,
            $this->getStrategy($taxableItemFactory, $multiplierType, $multiplierStrategy)->apply(
                $this->getCollectionWithSingleItem(
                    $taxableCollectionFactory,
                    $taxableItemFactory,
                    $currencyCode,
                    $type,
                    $gross
                )
            )
        );
    }

    /**
     * @param ItemFactoryInterface $factory
     * @param string $type
     * @param MultiplierInterface $multiplier
     * @return PricingConceptInterface
     */
    protected function getStrategy(ItemFactoryInterface $factory, $type, MultiplierInterface $multiplier)
    {
        return new AddMultiplierIncrement($factory, $type, $multiplier);
    }

    /**
     * @param float|int $multiplier
     * @return Multiplier
     */
    protected function getMultiplier($multiplier)
    {
        return new Multiplier($multiplier);
    }
}
