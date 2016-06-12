<?php

namespace Pb\Test\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\Strategy\AddCalculationFromCalculator;
use Pb\Domain\Calculator\Strategy\AddFixedAmount;

/**
 * Class AddCalculationFromCalculatorTest
 * @package Pb\Test\Domain\Calculator\Strategy
 */
class AddCalculationFromCalculatorTest extends CalculatorStrategyTest
{
    public function testItShouldDelegateToAnotherCalculator()
    {
        $gross = $this->getMoney(100.00);
        $otherCalculator = $this->getCalculator()
            ->addPriceBreakdown(new AddFixedAmount('test', $gross));

        $calculator = $this->getCalculator()->addPriceBreakdown($this->getStrategy($otherCalculator));

        $this->assertEquals(
            $gross,
            $calculator->calculate($this->getEmptyCollection())->gross()
        );
    }

    protected function getStrategy($otherCalculator = null)
    {
        return new AddCalculationFromCalculator($otherCalculator === null ? $this->getCalculator() : $otherCalculator);
    }
}
