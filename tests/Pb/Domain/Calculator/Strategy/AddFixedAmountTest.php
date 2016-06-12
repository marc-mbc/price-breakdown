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
        $type = 'basePrice';
        $currencyCode = 'EUR';
        $gross = $this->getMoney(120.24, $currencyCode);

        $expectedCollection = $this->getEmptyCollection($currencyCode);
        $expectedCollection->add(
            $type,
            $taxableItemFactory->buildWithGross($gross)
        );

        $this->assertEquals(
            $expectedCollection,
            $this->getStrategy($taxableItemFactory, $type, $gross)->apply(
                $this->getEmptyCollection($currencyCode)
            )
        );
    }

    /**
     * @param ItemFactoryInterface $factory
     * @param string $type
     * @param Money $gross
     * @return PricingConceptInterface
     */
    protected function getStrategy(ItemFactoryInterface $factory, $type, Money $gross)
    {
        return new AddFixedAmount($factory, $type, $gross);
    }
}
