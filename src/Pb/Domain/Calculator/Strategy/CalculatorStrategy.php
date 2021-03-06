<?php

namespace Pb\Domain\Calculator\Strategy;

use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Class CalculatorStrategy
 * @package Pb\Domain\Calculator\Strategy
 */
abstract class CalculatorStrategy
{
    /**
     * @var TaxableItemFactory
     */
    protected $taxableItemFactory;
    /**
     * @var TaxableCollectionFactory
     */
    protected $taxableCollectionFactory;

    /**
     * @param TaxableCollection $taxableCollection
     * @return TaxableCollection
     */
    abstract public function apply(TaxableCollection $taxableCollection);

    /**
     * @param TaxableItemFactory $factory
     * @return CalculatorStrategy
     */
    public function setTaxableItemFactory(TaxableItemFactory $factory)
    {
        $this->taxableItemFactory = $factory;
        return $this;
    }

    /**
     * @param TaxableCollectionFactory $factory
     * @return CalculatorStrategy
     */
    public function setTaxableCollectionFactory(TaxableCollectionFactory $factory)
    {
        $this->taxableCollectionFactory = $factory;
        return $this;
    }
}