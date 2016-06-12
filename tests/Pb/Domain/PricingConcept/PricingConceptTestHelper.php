<?php
namespace Pb\Test\Domain\PricingConcept;

use Pb\Domain\PricingConcept\Collection\TaxableCollectionFactory;
use Pb\Domain\PricingConcept\ValueObject\TaxableItemFactory;
use Pb\Test\Domain\DomainTestHelper;

/**
 * Class PricingConceptTestHelper
 * @package Pb\Test\Domain\PricingConcept
 */
abstract class PricingConceptTestHelper extends DomainTestHelper
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