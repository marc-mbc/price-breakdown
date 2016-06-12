<?php

namespace Pb\Domain\Calculator\Strategy;

use Money\Money;
use Pb\Domain\PriceBreakdown\CollectionInterface;

/**
 * Class AddFixedAmount
 * @package Pb\Domain\Calculator\Strategy
 */
class AddFixedAmount extends CalculatorStrategy
{
    /**
     * @var Money
     */
    protected $gross;
    /**
     * @var string
     */
    protected $conceptName;

    /**
     * AddAmount constructor.
     * @param string $conceptName
     * @param Money $gross
     */
    public function __construct($conceptName, Money $gross)
    {
        $this->conceptName = $conceptName;
        $this->gross = $gross;
    }

    /**
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function apply(CollectionInterface $collection)
    {
        return $collection->add(
            $this->conceptName,
            $this->itemFactory->buildWithGross($this->gross)
        );
    }
}