<?php
namespace Pb\Domain\PricingConcept\Collection;

use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\ItemInterface;
use Pb\Domain\PricingConcept\ValueObject\TaxableItem;
use Money\Currency;
use Money\Money;

/**
 * Class TaxableCollection
 * @package Domain\Entity\PricingConcept
 */
class TaxableCollection implements CollectionInterface
{
    /**
     * @var ItemInterface[]
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
     * @param string $type
     * @param ItemInterface $item
     * @return CollectionInterface
     */
    public function add($type, ItemInterface $item)
    {
        return $this->operate($type, $item, 'add');
    }

    /**
     * @param string $type
     * @param ItemInterface $item
     * @return CollectionInterface
     */
    public function subtract($type, ItemInterface $item)
    {
        return $this->operate($type, $item, 'subtract');
    }

    /**
     * @param string $type
     * @return CollectionInterface
     */
    public function find($type)
    {
        return isset($this->items[$type]) ? $this->items[$type] : null;
    }

    /**
     * @param string $type
     * @param ItemInterface $item
     * @param string $operation
     * @return CollectionInterface
     */
    protected function operate($type, ItemInterface $item, $operation)
    {
        if (!$this->currency->equals($item->gross()->getCurrency()))
        {
            throw new \InvalidArgumentException('New TaxableItem must operate in the same currency');
        }
        if (!isset($this->items[$type]))
        {
            $this->items[$type] = $item;
            $this->aggregate = new TaxableItem(
                $this->aggregate->net()->{$operation}($item->net()),
                $this->aggregate->vat()->{$operation}($item->vat())
            );
            return $this;
        }
        throw new \InvalidArgumentException('Taxable Item: ' . $type . ' already exists.');
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
     * @return ItemInterface
     */
    public function aggregate()
    {
        return $this->aggregate;
    }
}