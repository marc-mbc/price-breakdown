<?php

namespace Pb\Infrastructure\PriceBreakdown\ValueObject;

use Money\Money;
use Money\MoneyParser;
use Pb\Domain\Calculator\TaxApplicatorInterface;
use Pb\Domain\PriceBreakdown\ItemFactoryInterface;
use Pb\Domain\PriceBreakdown\ItemInterface;
use Pb\Domain\PriceBreakdown\ValueObject\TaxableItem;

/**
 * Class TaxableItemFactory
 * @package Pb\Domain\PriceBreakdown\ValueObject
 */
class TaxableItemFactory implements ItemFactoryInterface
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
     * @return ItemInterface
     */
    public function build(Money $net, Money $vat, Money $gross = null)
    {
        return new TaxableItem($net, $vat, $gross);
    }

    /**
     * @param Money $gross
     * @return ItemInterface
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
     * @return ItemInterface
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