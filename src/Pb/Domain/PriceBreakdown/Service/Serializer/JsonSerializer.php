<?php

namespace Pb\Domain\PriceBreakdown\Service\Serializer;

use Pb\Domain\PriceBreakdown\CollectionInterface;

/**
 * Class JsonSerializer
 * @package Pb\Domain\PriceBreakdown\Service\Serializer
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