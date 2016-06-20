<?php
namespace Pb\Domain\PriceBreakdown\TaxableCollection;

use Pb\Domain\PriceBreakdown\Taxable;
use Money\Currency;
use Money\Money;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItem;

/**
 * Class TaxableCollection
 * @package Pb\Domain\PriceBreakdown\TaxableCollection
 */
class TaxableCollection implements Taxable
{
    /**
     * @var Taxable[]
     */
    protected $items;
    /**
     * @var TaxableItem
     */
    protected $aggregate;

    /**
     * @var Currency
     */
    protected $currency;

    public function __construct(Currency $currency, TaxableItem $aggregate = null, array $items = [])
    {
        $money = new Money(0, $currency);
        $this->aggregate = $aggregate === null ? new TaxableItem($money, $money, $money) : $aggregate;
        $this->currency = $currency;
        $this->items = $items;
    }

    /**
     * @return Money
     */
    public function gross()
    {
        return $this->aggregate->gross();
    }

    /**
     * @return Money
     */
    public function net()
    {
        return $this->aggregate->net();
    }

    /**
     * @return Money
     */
    public function vat()
    {
        return $this->aggregate->vat();
    }

    /**
     * @param string $conceptName
     * @param Taxable $taxableItem
     * @return TaxableCollection
     */
    public function addUp($conceptName, Taxable $taxableItem)
    {
        return $this->operate($conceptName, $taxableItem, 'add');
    }

    /**
     * @param string $conceptName
     * @param Taxable $taxableItem
     * @return TaxableCollection
     */
    public function subtract($conceptName, Taxable $taxableItem)
    {
        return $this->operate($conceptName, $taxableItem, 'subtract');
    }

    /**
     * @param string $conceptName
     * @return TaxableCollection
     */
    public function find($conceptName)
    {
        return isset($this->items[$conceptName]) ? $this->items[$conceptName] : null;
    }

    /**
     * @param string $conceptName
     * @param Taxable $taxableItem
     * @param string $operation
     * @return TaxableCollection
     */
    protected function operate($conceptName, Taxable $taxableItem, $operation)
    {
        if (!$this->currency->equals($taxableItem->gross()->getCurrency()))
        {
            throw new \InvalidArgumentException('New Taxable must operate in the same currency');
        }
        if (!isset($this->items[$conceptName]))
        {
            $this->items[$conceptName] = $taxableItem;
            $this->aggregate = new TaxableItem(
                $this->aggregate->net()->{$operation}($taxableItem->net()),
                $this->aggregate->vat()->{$operation}($taxableItem->vat())
            );
            return $this;
        }
        throw new \InvalidArgumentException('Taxable Taxable: ' . $conceptName . ' already exists.');
    }

    /**
     * @return string[]
     */
    public function itemTypes()
    {
        return array_keys($this->items);
    }

    /**
     * @return Currency
     */
    public function currency()
    {
        return $this->currency;
    }

    /**
     * @return Taxable
     */
    public function aggregate()
    {
        return $this->aggregate;
    }

    /**
     * @param Taxable $taxable
     * @return bool
     */
    public function equals(Taxable $taxable)
    {
        return
            $this->aggregate->gross()->equals($taxable->gross()) &&
            $this->aggregate->net()->equals($taxable->net()) &&
            $this->aggregate->vat()->equals($taxable->vat());
    }
}