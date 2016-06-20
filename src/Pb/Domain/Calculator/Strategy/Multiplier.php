<?php

namespace Pb\Domain\Calculator\Strategy;

/**
 * Interface Multiplier
 * @package Pb\Domain\Calculator\Strategy
 */
interface Multiplier
{
    /**
     * @return float|int
     */
    public function multiplier();
}