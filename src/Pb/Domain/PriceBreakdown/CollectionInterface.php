<?php

namespace Pb\Domain\PriceBreakdown;

use Money\Currency;

/**
 * Interface CollectionInterface
 * @package Pb\Domain\PriceBreakdown
 */
interface CollectionInterface extends Taxable
{
    /**
     * @param string $conceptName
     * @param Taxable $item
     * @return CollectionInterface
     */
    public function addUp($conceptName, Taxable $item);
    /**
     * @param string $conceptName
     * @param Taxable $item
     * @return CollectionInterface
     */
    public function subtract($conceptName, Taxable $item);
    /**
     * @param string $conceptName
     * @return CollectionInterface
     */
    public function find($conceptName);

    /**
     * @return string[]
     */
    public function itemTypes();

    /**
     * @return Currency
     */
    public function currency();

    /**
     * @return Taxable
     */
    public function aggregate();
}
