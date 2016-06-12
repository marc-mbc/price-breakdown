<?php

namespace Pb\Domain\PricingConcept\Service\Serializer;

use Pb\Domain\PricingConcept\CollectionInterface;

/**
 * Class JsonSerializer
 * @package Pb\Domain\PricingConcept\Service\Serializer
 */
class JsonSerializer extends ArraySerializer
{
    /**
     * @param CollectionInterface $collection
     * @return array
     */
    public function serialize(CollectionInterface $collection)
    {
        return json_encode($this->getArrayFromCollection($collection));
    }

    /**
     * @param string $data
     *
     * @return CollectionInterface
     */
    public function unserialize($data)
    {
        return parent::unserialize(json_decode($data, true));
    }
}