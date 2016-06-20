<?php

namespace Pb\Domain\Calculator;

use Pb\Domain\Calculator\Strategy\CalculatorStrategy;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Class BasicCalculator
 * @package Pb\Domain\Calculator
 */
class BasicCalculator implements Calculator
{
    /**
     * @var CalculatorStrategy[]
     */
    protected $strategies = [];
    /**
     * @var TaxableCollectionFactory
     */
    protected $taxableCollectionFactory;
    /**
     * @var TaxableItemFactory
     */
    protected $taxableItemFactory;

    /**
     * BasicCalculator constructor.
     * @param TaxableCollectionFactory $taxableCollectionFactory
     * @param TaxableItemFactory $taxableItemFactory
     */
    public function __construct(TaxableCollectionFactory $taxableCollectionFactory, TaxableItemFactory $taxableItemFactory)
    {
        $this->taxableCollectionFactory = $taxableCollectionFactory;
        $this->taxableItemFactory = $taxableItemFactory;
    }

    /**
     * @param TaxableCollection $taxableCollection
     * @return TaxableCollection
     */
    public function calculate(TaxableCollection $taxableCollection)
    {
        foreach($this->strategies as $strategy)
        {
            $taxableCollection = $strategy->apply($taxableCollection);
        }
        return $taxableCollection;
    }

    /**
     * @param CalculatorStrategy $strategy
     * @param TaxableCollectionFactory $taxableCollectionFactory
     * @param TaxableItemFactory $itemFactory
     * @return Calculator
     */
    public function addStrategy(
        CalculatorStrategy $strategy,
        TaxableCollectionFactory $taxableCollectionFactory = null,
        TaxableItemFactory $itemFactory = null
    )
    {
        $strategy->setTaxableCollectionFactory(
            $taxableCollectionFactory === null ? $this->taxableCollectionFactory : $taxableCollectionFactory
        );
        $strategy->setTaxableItemFactory($itemFactory === null ? $this->taxableItemFactory : $itemFactory);
        $this->strategies[] = $strategy;
        return $this;
    }
}