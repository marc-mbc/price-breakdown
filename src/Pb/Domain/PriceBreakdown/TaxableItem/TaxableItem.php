<?php
namespace Pb\Domain\PriceBreakdown\TaxableItem;

use Money\Money;
use Pb\Domain\PriceBreakdown\Taxable;

/**
 * Class Taxable
 * @package Pb\Domain\PriceBreakdown\Taxable
 */
class TaxableItem implements Taxable
{
    /**
     * @var Money
     */
    protected $net;
    /**
     * @var Money
     */
    protected $vat;
    /**
     * @var Money
     */
    protected $gross;

    public function __construct(Money $net, Money $vat, Money $gross = null)
    {
        $this->checkCurrencies($net, $vat, $gross);
        $this->net = $net;
        $this->vat = $vat;
        $this->gross = $gross === null ? $net->add($vat): $gross;
    }

    /**
     * @return Money
     */
    public function gross()
    {
        return $this->gross;
    }

    /**
     * @return Money
     */
    public function net()
    {
        return $this->net;
    }

    /**
     * @return Money
     */
    public function vat()
    {
        return $this->vat;
    }

    /**
     * @param Money $net
     * @param Money $vat
     * @param Money $gross
     */
    protected function checkCurrencies(Money $net, Money $vat, Money $gross = null)
    {
        if (
            !$net->getCurrency()->equals($vat->getCurrency()) ||
            ($gross !== null && !$net->getCurrency()->equals($gross->getCurrency()))
        )
        {
            throw new \InvalidArgumentException('All prices must be in the same Currency');
        }
    }

    /**
     * @param Taxable $taxable
     * @return bool
     */
    public function equals(Taxable $taxable)
    {
        return
            $this->gross->equals($taxable->gross()) &&
            $this->net->equals($taxable->net()) &&
            $this->vat->equals($taxable->vat());
    }
}