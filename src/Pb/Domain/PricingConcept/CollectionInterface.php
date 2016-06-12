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
     * @param string $type
     * @param ItemInterface $item
     * @return CollectionInterface
     */
    public function add($type, ItemInterface $item);
    /**
     * @param string $type
     * @param ItemInterface $item
     * @return CollectionInterface
     */
    public function subtract($type, ItemInterface $item);
    /**
     * @param string $type
     * @return CollectionInterface
     */
    public function find($type);

    /**
     * @return string[]
     */
    public function itemTypes();

    /**
     * @return Currency
     */
    public function currency();
}
