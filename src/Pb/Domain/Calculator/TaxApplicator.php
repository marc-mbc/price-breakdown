<?php

namespace Pb\Domain\Calculator;

use Money\Money;

/**
 * Class TaxApplicator
 * @package Pb\Domain\Calculator
 */
class TaxApplicator implements TaxApplicatorInterface
{
    /**
     * @var int|float
     */
    protected $vatToApply;
    /**
     * @var int|float
     */
    protected $netApply;

    /**
     * TaxApplicator constructor.
     * @param int|float $vatToApply
     */
    public function __construct($vatToApply = 0)
    {
        if (!is_numeric($vatToApply) || $vatToApply > 1 || $vatToApply < 0)
        {
            throw new \InvalidArgumentException('Vat to apply must be [0-1] float');
        }
        $this->vatToApply = $vatToApply;
        $this->netApply = 1 + $vatToApply;
    }

    /**
     * @param Money $gross
     * @return Money
     */
    public function netFromGross(Money $gross)
    {
        return $gross->divide($this->netApply);
    }

    /**
     * @param Money $net
     * @return Money
     */
    public function vatFromNet(Money $net)
    {
        return $net->multiply($this->vatToApply);
    }
}