<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\ItemFactoryInterface;
use Pb\Domain\PricingConcept\PricingConceptInterface;

/**
 * Class AddMultiplierIncrement
 * @package Pb\Domain\Calculator\Strategy
 */
class AddMultiplierIncrement  implements PricingConceptInterface
{
    /**
     * @var MultiplierInterface
     */
    protected $multiplier;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var float
     */
    protected $vatToApply;
    /**
     * @var ItemFactoryInterface
     */
    protected $itemFactory;

    /**
     * AddAmount constructor.
     * @param ItemFactoryInterface $itemFactory
     * @param string $type
     * @param MultiplierInterface $multiplier
     */
    public function __construct(ItemFactoryInterface $itemFactory, $type, MultiplierInterface $multiplier)
    {
        $this->itemFactory = $itemFactory;
        $this->type = $type;
        $this->multiplier = $multiplier;
    }
    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function apply(CollectionInterface $collection)
    {
        return $collection->add(
            $this->type,
            $this->itemFactory->buildWithGross($collection->gross()->multiply($this->multiplier->multiplier()))
        );
    }
}