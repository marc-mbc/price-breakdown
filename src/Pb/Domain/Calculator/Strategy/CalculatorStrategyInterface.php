<?php

namespace Pb\Domain\PriceBreakdown;

use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Interface CalculatorStrategyInterface
 * @package Pb\Domain\PriceBreakdown
 */
interface CalculatorStrategyInterface
{
    /**
     * @param TaxableCollection $taxableCollection
     * @return TaxableCollection
     */
    public function apply(TaxableCollection $taxableCollection);

    /**
     * @param TaxableItemFactory $factory
     * @return CalculatorStrategyInterface
     */
    public function setTaxableItemFactory(TaxableItemFactory $factory);

    /**
     * @param TaxableCollectionFactory $factory
     * @return CalculatorStrategyInterface
     */
    public function setTaxableCollectionFactory(TaxableCollectionFactory $factory);
}