<?php

namespace Pb\Test\Domain\Calculator\Strategy;

use Money\Money;
use Pb\Domain\Calculator\Strategy\AddFixedAmount;
use Pb\Domain\Calculator\Strategy\CalculatorStrategy;

/**
 * Class AddFixedAmountTest
 * @package Pb\Test\Domain\Calculator\Strategy
 */
class AddFixedAmountTest extends CalculatorStrategyTest
{
    public function testStrategyWorksAsExpected()
    {
        $taxableItemFactory = $this->getTaxableItemFactory();
        $conceptName = 'basePrice';
        $currencyCode = 'EUR';
        $gross = $this->getMoney(120.24, $currencyCode);

        $expectedCollection = $this->getEmptyCollection($currencyCode);
        $expectedCollection->addUp(
            $conceptName,
            $taxableItemFactory->buildWithGross($gross)
        );

        $strategy = $this->getStrategy($conceptName, $gross);
        $strategy->setTaxableCollectionFactory($this->getTaxableCollectionFactory());
        $strategy->setTaxableItemFactory($taxableItemFactory);

        $this->assertEquals(
            $expectedCollection,
            $strategy->apply(
                $this->getEmptyCollection($currencyCode)
            )
        );
    }

    /**
     * @param string $conceptName
     * @param Money $gross
     * @return CalculatorStrategy
     */
    protected function getStrategy($conceptName = 'default', Money $gross = null)
    {
        return new AddFixedAmount(
            $conceptName,
            $gross === null ? $this->getMoney(20.25) : $gross
        );
    }
}
