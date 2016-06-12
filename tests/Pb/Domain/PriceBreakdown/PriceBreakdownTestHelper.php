<?php
namespace Pb\Test\Domain\PriceBreakdown;

use Pb\Domain\PriceBreakdown\Collection\TaxableCollectionFactory;
use Pb\Domain\PriceBreakdown\ValueObject\TaxableItemFactory;
use Pb\Test\Domain\DomainTestHelper;

/**
 * Class PriceBreakdownTestHelper
 * @package Pb\Test\Domain\PriceBreakdown
 */
abstract class PriceBreakdownTestHelper extends DomainTestHelper
{
    /**
     * @param int|float $vatToApply
     * @return TaxableItemFactory
     */
    protected function getItemFactory($vatToApply = 0)
    {
        return new TaxableItemFactory($this->getMoneyParser(), $vatToApply);
    }

    /**
     * @return TaxableCollectionFactory
     */
    protected function getCollectionFactory()
    {
        return new TaxableCollectionFactory();
    }
}