<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\PriceBreakdown\CollectionInterface;

/**
 * Class GroupAsCollection
 * @package Pb\Domain\Calculator\Strategy
 */
class GroupAsCollection extends CalculatorStrategy
{
    /**
     * @var string
     */
    protected $conceptName;

    /**
     * AddNewCollectionOnTop constructor.
     * @param string $conceptName
     */
    public function __construct($conceptName)
    {
        $this->conceptName = $conceptName;
    }

    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function apply(CollectionInterface $collection)
    {
        return $this->collectionFactory->build(
            $collection->currency(), $collection->aggregate() , [$this->conceptName => $collection]
        );
    }
}