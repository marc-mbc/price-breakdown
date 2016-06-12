<?php

namespace Pb\Domain\PricingConcept;

/**
 * Interface PricingConceptInterface
 * @package Pb\Domain\PricingConcept
 */
interface PricingConceptInterface
{
    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function apply(CollectionInterface $collection);
}