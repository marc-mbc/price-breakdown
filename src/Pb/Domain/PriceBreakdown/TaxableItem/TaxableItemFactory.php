<?php

namespace Pb\Domain\PriceBreakdown\TaxableItem;

use Money\Money;
use Money\MoneyParser;
use Pb\Domain\Calculator\TaxApplicator;
use Pb\Domain\PriceBreakdown\Taxable;

/**
 * Class TaxableItemFactory
 * @package Pb\Domain\PriceBreakdown\TaxableItem
 */
class TaxableItemFactory
{
    /**
     * @var MoneyParser
     */
    protected $moneyParser;
    /**
     * @var TaxApplicator
     */
    protected $taxApplicator;

    /**
     * TaxableItemFactory constructor.
     * @param MoneyParser $moneyParser
     * @param TaxApplicator $taxApplicator
     */
    public function __construct(MoneyParser $moneyParser, TaxApplicator $taxApplicator)
    {
        $this->moneyParser = $moneyParser;
        $this->taxApplicator = $taxApplicator;
    }

    /**
     * @param Money $net
     * @param Money $vat
     * @param Money|null $gross
     * @return Taxable
     */
    public function build(Money $net, Money $vat, Money $gross = null)
    {
        return new TaxableItem($net, $vat, $gross);
    }

    /**
     * @param Money $gross
     * @return Taxable
     */
    public function buildWithGross(Money $gross)
    {
        $net = $this->taxApplicator->netFromGross($gross);
        return $this->build($net, $gross->subtract($net), $gross);
    }

    /**
     * @param string $currency
     * @param float $net
     * @param float $vat
     * @param float|null $gross
     * @return Taxable
     */
    public function buildFromBasicTypes($currency, $net, $vat, $gross = null)
    {
        return new TaxableItem(
            $this->moneyParser->parse($net, $currency),
            $this->moneyParser->parse($vat, $currency),
            $gross === null ? null : $this->moneyParser->parse($gross, $currency)
        );
    }
}