<?php

namespace Pb\Domain\Calculator\Strategy;

use Money\Money;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;

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
     * @param TaxableCollection $taxableCollection
     * @return TaxableCollection
     */
    public function apply(TaxableCollection $taxableCollection)
    {
        return $taxableCollection->addUp(
            $this->conceptName,
            $this->taxableItemFactory->buildWithGross($this->gross)
        );
    }
}