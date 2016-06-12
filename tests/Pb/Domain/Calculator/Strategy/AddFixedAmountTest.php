<?php

namespace Pb\Test\Domain\Calculator\Strategy;
use Money\Money;
use Pb\Domain\Calculator\Strategy\AddFixedAmount;
use Pb\Domain\PricingConcept\ItemFactoryInterface;
use Pb\Domain\PricingConcept\PricingConceptInterface;
use Pb\Test\Domain\Calculator\CalculatorTestHelper;

/**
 * Class AddFixedAmountTest
 * @package Pb\Test\Domain\Calculator\Strategy
 */
class AddFixedAmountTest extends CalculatorTestHelper
{
    public function testStrategyWorksAsExpected()
    {
        $taxableItemFactory = $this->getItemFactory();
        $conceptName = 'basePrice';
        $currencyCode = 'EUR';
        $gross = $this->getMoney(120.24, $currencyCode);

        $expectedCollection = $this->getEmptyCollection($currencyCode);
        $expectedCollection->add(
            $conceptName,
            $taxableItemFactory->buildWithGross($gross)
        );

        $this->assertEquals(
            $expectedCollection,
            $this->getStrategy($taxableItemFactory, $conceptName, $gross)->apply(
                $this->getEmptyCollection($currencyCode)
            )
        );
    }

    /**
     * @param ItemFactoryInterface $factory
     * @param string $conceptName
     * @param Money $gross
     * @return PricingConceptInterface
     */
    protected function getStrategy(ItemFactoryInterface $factory, $conceptName, Money $gross)
    {
        return new AddFixedAmount($factory, $conceptName, $gross);
    }
}
