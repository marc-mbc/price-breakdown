<?php

namespace Pb\Domain\Calculator\Strategy;

/**
 * Interface MultiplierInterface
 * @package Pb\Domain\Calculator\Strategy
 */
interface MultiplierInterface
{
    /**
     * @return float|int
     */
    public function multiplier();
}