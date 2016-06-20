<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;

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
     * @param TaxableCollection $taxableCollection
     * @return TaxableCollection
     */
    public function apply(TaxableCollection $taxableCollection)
    {
        return $this->taxableCollectionFactory->build(
            $taxableCollection->currency(), $taxableCollection->aggregate() , [$this->conceptName => $taxableCollection]
        );
    }
}