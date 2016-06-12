<?php

namespace Pb\Domain\PriceBreakdown\Service\Serializer;

use Pb\Domain\PriceBreakdown\CollectionInterface;

/**
 * Interface SerializerInterface
 * @package Pb\Domain\PriceBreakdown\Service\Serializer
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