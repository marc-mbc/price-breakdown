<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;

/**
 * Class AddPercentage
 * @package Pb\Domain\Calculator\Strategy
 */
class AddPercentage extends CalculatorStrategy
{
    /**
     * @var MultiplierInterface
     */
    protected $multiplier;
    /**
     * @var string
     */
    protected $conceptName;

    /**
     * AddAmount constructor.
     * @param string $conceptName
     * @param MultiplierInterface $multiplier
     */
    public function __construct($conceptName, MultiplierInterface $multiplier)
    {
        $this->conceptName = $conceptName;
        $this->multiplier = $multiplier;
    }
    /**
     * @param TaxableCollection $taxableCollection
     * @return TaxableCollection
     */
    public function apply(TaxableCollection $taxableCollection)
    {
        return $taxableCollection->addUp(
            $this->conceptName,
            $this->taxableItemFactory->buildWithGross($taxableCollection->gross()->multiply($this->multiplier->multiplier()))
        );
    }
}