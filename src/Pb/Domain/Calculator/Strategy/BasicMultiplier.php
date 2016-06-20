<?php

namespace Pb\Domain\Calculator\Strategy;

/**
 * Class BasicMultiplier
 * @package Pb\Domain\Calculator\Strategy
 */
class BasicMultiplier implements Multiplier
{
    /**
     * @var float|int
     */
    protected $multiplier;

    /**
     * BasicMultiplier constructor.
     * @param float|int $multiplier
     */
    public function __construct($multiplier)
    {
        $this->multiplier = $multiplier;
    }

    /**
     * @return float|int
     */
    public function multiplier()
    {
        return $this->multiplier;
    }
}