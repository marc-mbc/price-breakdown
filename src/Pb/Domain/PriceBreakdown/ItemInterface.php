<?php

namespace Pb\Domain\PriceBreakdown;

use Money\Money;

/**
 * Interface ItemInterface
 * @package Pb\Domain\PriceBreakdown
 */
interface ItemInterface
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
