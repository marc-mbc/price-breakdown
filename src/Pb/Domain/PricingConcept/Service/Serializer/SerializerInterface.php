<?php

namespace Pb\Domain\PricingConcept\Service\Serializer;

use Pb\Domain\PricingConcept\CollectionInterface;

/**
 * Interface SerializerInterface
 * @package Pb\Domain\PricingConcept\Service\Serializer
 */
interface SerializerInterface
{
    /**
     * @param CollectionInterface $collection
     * @return mixed
     */
    public function serialize(CollectionInterface $collection);
    /**
     * @param mixed $data
     * @return CollectionInterface
     */
    public function unserialize($data);
}