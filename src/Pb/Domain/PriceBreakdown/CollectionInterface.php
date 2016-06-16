<?php

namespace Pb\Domain\PriceBreakdown;

use Money\Currency;

/**
 * Interface CollectionInterface
 * @package Pb\Domain\PriceBreakdown
 */
interface CollectionInterface extends ItemInterface
{
    /**
     * @param string $conceptName
     * @param ItemInterface $item
     * @return CollectionInterface
     */
    public function addUp($conceptName, ItemInterface $item);
    /**
     * @param string $conceptName
     * @param ItemInterface $item
     * @return CollectionInterface
     */
    public function subtract($conceptName, ItemInterface $item);
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
     * @return ItemInterface
     */
    public function aggregate();
}
