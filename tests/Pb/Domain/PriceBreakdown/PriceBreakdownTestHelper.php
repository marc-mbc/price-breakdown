<?php

namespace Pb\Test\Domain\PriceBreakdown;

use Pb\Domain\Calculator\TaxApplicatorInterface;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;
use Pb\Test\Domain\DomainTestHelper;

/**
 * Class PriceBreakdownTestHelper
 * @package Pb\Test\Domain\PriceBreakdown
 */
abstract class PriceBreakdownTestHelper extends DomainTestHelper
{
    /**
     * @param TaxApplicatorInterface $taxApplicator
     * @return TaxableItemFactory
     */
    protected function getTaxableItemFactory(TaxApplicatorInterface $taxApplicator = null)
    {
        return new TaxableItemFactory(
            $this->getMoneyParser(),
            $taxApplicator === null ? $this->getTaxApplicator() : $taxApplicator
        );
    }

    /**
     * @return TaxableCollectionFactory
     */
    protected function getTaxableCollectionFactory()
    {
        return new TaxableCollectionFactory();
    }
}