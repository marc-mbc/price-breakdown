<?php

namespace Pb\Domain\Calculator;
use Money\Money;

/**
 * Interface TaxApplicatorInterface
 * @package Pb\Domain\Calculator
 */
interface TaxApplicatorInterface
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