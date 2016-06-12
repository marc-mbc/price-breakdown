<?php

namespace Pb\Domain\Calculator\Strategy;

use Money\Money;
use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\ItemFactoryInterface;
use Pb\Domain\PricingConcept\PricingConceptInterface;

/**
 * Class AddFixedAmount
 * @package Pb\Domain\Calculator\Strategy
 */
class AddFixedAmount implements PricingConceptInterface
{
    /**
     * @var Money
     */
    protected $gross;
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
     * @param Money $gross
     */
    public function __construct(ItemFactoryInterface $itemFactory, $type, $gross)
    {
        $this->itemFactory = $itemFactory;
        $this->type = $type;
        $this->gross = $gross;
    }

    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function apply(CollectionInterface $collection)
    {
        return $collection->add(
            $this->type,
            $this->itemFactory->buildWithGross($this->gross)
        );
    }
}