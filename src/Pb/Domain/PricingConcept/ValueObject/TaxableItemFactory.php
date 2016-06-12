<?php

namespace Pb\Domain\PricingConcept\ValueObject;

use Money\Money;
use Money\MoneyParser;
use Pb\Domain\PricingConcept\ItemFactoryInterface;
use Pb\Domain\PricingConcept\ItemInterface;

/**
 * Class TaxableItemFactory
 * @package Pb\Domain\PricingConcept\ValueObject
 */
class TaxableItemFactory implements ItemFactoryInterface
{
    /**
     * @var MoneyParser
     */
    protected $moneyParser;
    /**
     * @var float
     */
    protected $vatToApply;

    /**
     * TaxableItemFactory constructor.
     * @param MoneyParser $moneyParser
     * @param float|int $vatToApply
     */
    public function __construct(MoneyParser $moneyParser, $vatToApply = 0)
    {
        $this->moneyParser = $moneyParser;
        if (!is_numeric($vatToApply) || $vatToApply > 1 || $vatToApply < 0)
        {
            throw new \InvalidArgumentException('Vat to apply must be [0-1] float');
        }
        $this->vatToApply = 1 + $vatToApply;
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
        $net = $gross->divide($this->vatToApply);
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