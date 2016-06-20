<?php

namespace Pb\Domain\Calculator;
use Money\Money;

/**
 * Interface TaxApplicator
 * @package Pb\Domain\Calculator
 */
interface TaxApplicator
{
    /**
     * @param Money $gross
     * @return Money
     */
    public function netFromGross(Money $gross);

    /**
     * @param Money $net
     * @return Money
     */
    public function vatFromNet(Money $net);
}