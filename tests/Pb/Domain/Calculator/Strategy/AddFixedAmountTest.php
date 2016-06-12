<?php

namespace Pb\Test\Domain\Calculator\Strategy;
use Money\Money;
use Pb\Domain\Calculator\Strategy\AddFixedAmount;
use Pb\Domain\PriceBreakdown\CalculatorStrategyInterface;

/**
 * Class AddFixedAmountTest
 * @package Pb\Test\Domain\Calculator\Strategy
 */
class AddFixedAmountTest extends CalculatorStrategyTest
{
    public function testStrategyWorksAsExpected()
    {
        $itemFactory = $this->getItemFactory();
        $conceptName = 'basePrice';
        $currencyCode = 'EUR';
        $gross = $this->getMoney(120.24, $currencyCode);

        $expectedCollection = $this->getEmptyCollection($currencyCode);
        $expectedCollection->add(
            $conceptName,
            $itemFactory->buildWithGross($gross)
        );

        $strategy = $this->getStrategy($conceptName, $gross);
        $strategy->setCollectionFactory($this->getCollectionFactory());
        $strategy->setItemFactory($itemFactory);
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
     * @return CalculatorStrategyInterface
     */
    protected function getStrategy($conceptName = 'default', Money $gross = null)
    {
        return new AddFixedAmount(
            $conceptName,
            $gross === null ? $this->getMoney(20.25) : $gross
        );
    }
}
