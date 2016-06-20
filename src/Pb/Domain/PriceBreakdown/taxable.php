<?php

namespace Pb\Domain\PriceBreakdown;

use Money\Money;

/**
 * Interface Taxable
 * @package Pb\Domain\PriceBreakdown
 */
interface Taxable
{
    /**
     * @return Money
     */
    public function gross();
    /**
     * @return Money
     */
    public function net();
    /**
     * @return Money
     */
    public function vat();
}
