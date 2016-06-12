<?php

namespace Pb\Domain\PriceBreakdown;

use Money\Money;

/**
 * Interface ItemFactoryInterface
 * @package Pb\Domain\PriceBreakdown
 */
interface ItemFactoryInterface
{
    /**
     * @param Money $net
     * @param Money $vat
     * @param Money|null $gross
     * @return ItemInterface
     */
    public function build(Money $net, Money $vat, Money $gross = null);

    /**
     * @param string $currency
     * @param float $net
     * @param float $vat
     * @param float|null $gross
     * @return ItemInterface
     */
    public function buildFromBasicTypes($currency, $net, $vat, $gross = null);

    /**
     * @param Money $gross
     * @return ItemInterface
     */
    public function buildWithGross(Money $gross);
}