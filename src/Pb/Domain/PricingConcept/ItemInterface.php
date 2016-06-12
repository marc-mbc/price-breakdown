<?php

namespace Pb\Domain\PricingConcept;

use Money\Money;

/**
 * Interface ItemInterface
 * @package Pb\Domain\PricingConcept
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
