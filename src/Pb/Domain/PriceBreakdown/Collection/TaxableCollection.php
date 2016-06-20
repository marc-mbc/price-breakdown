<?php
namespace Pb\Domain\PriceBreakdown\Collection;

use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\Taxable;
use Money\Currency;
use Money\Money;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItem;

/**
 * Class TaxableCollection
 * @package Domain\Entity\PriceBreakdown
 */
class TaxableCollection implements CollectionInterface
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
     * @param Taxable $item
     * @return CollectionInterface
     */
    public function addUp($conceptName, Taxable $item)
    {
        return $this->operate($conceptName, $item, 'add');
    }

    /**
     * @param string $conceptName
     * @param Taxable $item
     * @return CollectionInterface
     */
    public function subtract($conceptName, Taxable $item)
    {
        return $this->operate($conceptName, $item, 'subtract');
    }

    /**
     * @param string $conceptName
     * @return CollectionInterface
     */
    public function find($conceptName)
    {
        return isset($this->items[$conceptName]) ? $this->items[$conceptName] : null;
    }

    /**
     * @param string $conceptName
     * @param Taxable $item
     * @param string $operation
     * @return CollectionInterface
     */
    protected function operate($conceptName, Taxable $item, $operation)
    {
        if (!$this->currency->equals($item->gross()->getCurrency()))
        {
            throw new \InvalidArgumentException('New TaxableItem must operate in the same currency');
        }
        if (!isset($this->items[$conceptName]))
        {
            $this->items[$conceptName] = $item;
            $this->aggregate = new TaxableItem(
                $this->aggregate->net()->{$operation}($item->net()),
                $this->aggregate->vat()->{$operation}($item->vat())
            );
            return $this;
        }
        throw new \InvalidArgumentException('Taxable TaxableItem: ' . $conceptName . ' already exists.');
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
}