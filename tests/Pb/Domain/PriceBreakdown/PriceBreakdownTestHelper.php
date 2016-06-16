<?php

namespace Pb\Test\Domain\PriceBreakdown;

use Pb\Domain\Calculator\TaxApplicatorInterface;
use Pb\Infrastructure\PriceBreakdown\Collection\TaxableCollectionFactory;
use Pb\Infrastructure\PriceBreakdown\ValueObject\TaxableItemFactory;
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
    protected function getItemFactory(TaxApplicatorInterface $taxApplicator = null)
    {
        return new TaxableItemFactory(
            $this->getMoneyParser(),
            $taxApplicator === null ? $this->getTaxApplicator() : $taxApplicator
        );
    }

    /**
     * @return TaxableCollectionFactory
     */
    protected function getCollectionFactory()
    {
        return new TaxableCollectionFactory();
    }
}