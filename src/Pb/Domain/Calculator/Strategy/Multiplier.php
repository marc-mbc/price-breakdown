<?php

namespace Pb\Domain\Calculator\Strategy;

/**
 * Class Multiplier
 * @package Pb\Domain\Calculator\Strategy
 */
class Multiplier implements MultiplierInterface
{
    /**
     * @var float|int
     */
    protected $multiplier;

    /**
     * Multiplier constructor.
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