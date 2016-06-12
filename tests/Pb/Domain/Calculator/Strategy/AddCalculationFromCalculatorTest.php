<?php

namespace Pb\Test\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\Strategy\AddCalculationFromCalculator;
use Pb\Domain\Calculator\Strategy\AddFixedAmount;
use Pb\Test\Domain\Calculator\CalculatorTestHelper;

/**
 * Class AddCalculationFromCalculatorTest
 * @package Pb\Test\Domain\Calculator\Strategy
 */
class AddCalculationFromCalculatorTest extends CalculatorTestHelper
{
    public function testItShouldDelegateToAnotherCalculator()
    {
        $gross = $this->getMoney(100.00);
        $otherCalculator = $this->getCalculator()
            ->addPricingConcept(new AddFixedAmount($this->getItemFactory(), 'test', $gross));

        $calculator = $this->getCalculator()->addPricingConcept(new AddCalculationFromCalculator($otherCalculator));

        $this->assertEquals(
            $gross,
            $calculator->calculate($this->getEmptyCollection())->gross()
        );
    }
}
