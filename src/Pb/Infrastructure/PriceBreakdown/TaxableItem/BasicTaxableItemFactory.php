<?php

namespace Pb\Infrastructure\PriceBreakdown\TaxableItem;

use Money\Money;
use Money\MoneyParser;
use Pb\Domain\Calculator\TaxApplicatorInterface;
use Pb\Domain\PriceBreakdown\Taxable;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItem;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Class BasicTaxableItemFactory
 * @package Pb\Infrastructure\PriceBreakdown\TaxableItem
 */
class BasicTaxableItemFactory implements TaxableItemFactory
{
    /**
     * @var MoneyParser
     */
    protected $moneyParser;
    /**
     * @var TaxApplicatorInterface
     */
    protected $taxApplicator;

    /**
     * TaxableItemFactory constructor.
     * @param MoneyParser $moneyParser
     * @param TaxApplicatorInterface $taxApplicator
     */
    public function __construct(MoneyParser $moneyParser, TaxApplicatorInterface $taxApplicator)
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