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

    /**
     * @param ItemFactoryInterface $factory
     * @return PricingConceptInterface
     */
    public function setItemFactory(ItemFactoryInterface $factory);

    /**
     * @param CollectionFactoryInterface $factory
     * @return PricingConceptInterface
     */
    public function setCollectionFactory(CollectionFactoryInterface $factory);
}