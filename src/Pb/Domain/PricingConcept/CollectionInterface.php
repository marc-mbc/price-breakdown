<?php

namespace Pb\Domain\PricingConcept;

use Money\Currency;

/**
 * Interface CollectionInterface
 * @package Pb\Domain\PricingConcept
 */
interface CollectionInterface extends ItemInterface
{
    /**
     * @param string $conceptName
     * @param ItemInterface $item
     * @return CollectionInterface
     */
    public function add($conceptName, ItemInterface $item);
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
