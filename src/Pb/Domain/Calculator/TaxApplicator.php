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
    protected $taxToApply;
    /**
     * @var int|float
     */
    protected $grossApply;

    /**
     * TaxApplicator constructor.
     * @param int|float $taxToApply
     */
    public function __construct($taxToApply = 0)
    {
        $this->checkValidTax($taxToApply);
        $this->taxToApply = $taxToApply;
        $this->grossApply = 1 + $taxToApply;
    }

    /**
     * @param Money $gross
     * @return Money
     */
    public function netFromGross(Money $gross)
    {
        return $gross->divide($this->grossApply);
    }

    /**
     * @param Money $net
     * @return Money
     */
    public function vatFromNet(Money $net)
    {
        return $net->multiply($this->taxToApply);
    }

    /**
     * @param $taxToApply
     */
    protected function checkValidTax($taxToApply)
    {
        if (!is_numeric($taxToApply) || $taxToApply > 1 || $taxToApply < 0)
        {
            throw new \InvalidArgumentException('Vat to apply must be [0-1] float');
        }
    }
}