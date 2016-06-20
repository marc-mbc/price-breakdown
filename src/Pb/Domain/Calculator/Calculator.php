<?php

namespace Pb\Domain\Calculator;

use Pb\Domain\PriceBreakdown\CalculatorStrategyInterface;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Class Calculator
 * @package Pb\Domain\Calculator
 */
class Calculator implements CalculatorInterface
{
    /**
     * @var CalculatorStrategyInterface[]
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
     * Calculator constructor.
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
     * @param CalculatorStrategyInterface $strategy
     * @param TaxableCollectionFactory $taxableCollectionFactory
     * @param TaxableItemFactory $itemFactory
     * @return CalculatorInterface
     */
    public function addStrategy(
        CalculatorStrategyInterface $strategy,
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