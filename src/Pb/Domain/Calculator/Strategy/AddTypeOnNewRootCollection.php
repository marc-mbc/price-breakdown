<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\PricingConcept\CollectionFactoryInterface;
use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\PricingConceptInterface;

/**
 * Class AddTypeOnNewRootCollection
 * @package Pb\Domain\Calculator\Strategy
 */
class AddTypeOnNewRootCollection implements PricingConceptInterface
{
    /**
     * @var string
     */
    protected $type;
    /**
     * @var CollectionFactoryInterface
     */
    protected $factory;

    /**
     * AddNewCollectionOnTop constructor.
     * @param CollectionFactoryInterface $factory
     * @param string $type
     */
    public function __construct(CollectionFactoryInterface $factory, $type)
    {
        $this->factory = $factory;
        $this->type = $type;
    }

    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function apply(CollectionInterface $collection)
    {
        return $this->factory->build($collection->currency(), $collection->aggregate() , [$this->type => $collection]);
    }
}