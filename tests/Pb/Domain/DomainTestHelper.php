<?php
namespace Pb\Test\Domain;

use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\Parser\StringToUnitsParser;
use Pb\Domain\Calculator\TaxApplicator;
use Pb\Domain\Calculator\TaxApplicatorInterface;

/**
 * Class DomainTestHelper
 * @package Pb\Test\Domain
 */
abstract class DomainTestHelper extends \PHPUnit_Framework_TestCase
{
    /**
     * @param float|int $amount
     * @param string $currency
     * @return Money
     * @throws \Money\Exception\ParserException
     */
    protected function getMoney($amount, $currency = 'EUR')
    {
        return $this->getMoneyParser()->parse($amount, $currency);
    }

    /**
     * @return StringToUnitsParser
     */
    protected function getMoneyParser()
    {
        return new StringToUnitsParser();
    }

    /**
     * @return IntlMoneyFormatter
     */
    protected function getMoneyFormatter()
    {
        return new IntlMoneyFormatter(new \NumberFormatter('en_GB', \NumberFormatter::PATTERN_DECIMAL, '0.00'));
    }

    /**
     * @param $currency
     * @return Currency
     */
    protected function getCurrency($currency)
    {
        return new Currency($currency);
    }

    /**
     * @param int|float $taxToApply
     * @return TaxApplicatorInterface
     */
    protected function getTaxApplicator($taxToApply = 0)
    {
        return new TaxApplicator($taxToApply);
    }
}