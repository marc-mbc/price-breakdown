<?php

namespace Pb\Domain\PriceBreakdown\TaxableItem;

use Money\Money;
use Pb\Domain\PriceBreakdown\Taxable;

/**
 * Interface TaxableItemFactory
 * @package Pb\Domain\PriceBreakdown\TaxableItem
 */
interface TaxableItemFactory
{
    /**
     * @param Money $net
     * @param Money $vat
     * @param Money|null $gross
     * @return Taxable
     */
    public function build(Money $net, Money $vat, Money $gross = null);

    /**
     * @param string $currency
     * @param float $net
     * @param float $vat
     * @param float|null $gross
     * @return Taxable
     */
    public function buildFromBasicTypes($currency, $net, $vat, $gross = null);

    /**
     * @param Money $gross
     * @return Taxable
     */
    public function buildWithGross(Money $gross);
}