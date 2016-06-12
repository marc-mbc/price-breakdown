<?php

namespace Pb\Domain\PricingConcept\Service\Serializer;

use Money\Currency;
use Money\MoneyFormatter;
use Pb\Domain\PricingConcept\CollectionFactoryInterface;
use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\ItemFactoryInterface;
use Pb\Domain\PricingConcept\ItemInterface;

/**
 * Class ArraySerializer
 * @package Pb\Domain\PricingConcept\Service\Serializer
 */
class ArraySerializer implements SerializerInterface
{
    const AGGREGATE = 'aggregate';
    const ITEMS = 'items';
    const CURRENCY = 'currency';
    const GROSS = 'gross';
    const NET = 'net';
    const VAT = 'vat';
    /**
     * @var MoneyFormatter
     */
    protected $moneyFormatter;
    /**
     * @var CollectionFactoryInterface
     */
    protected $collectionFactory;
    /**
     * @var ItemFactoryInterface
     */
    protected $itemFactory;

    /**
     * ArraySerializer constructor.
     * @param CollectionFactoryInterface $collectionFactory
     * @param ItemFactoryInterface $itemFactory
     * @param MoneyFormatter $moneyFormatter
     */
    public function __construct(
        CollectionFactoryInterface $collectionFactory, 
        ItemFactoryInterface $itemFactory,
        MoneyFormatter $moneyFormatter
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->itemFactory = $itemFactory;
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * @param CollectionInterface $collection
     * @return array
     */
    public function serialize(CollectionInterface $collection)
    {
        return $this->getArrayFromCollection($collection);
    }

    /**
     * @param array $data
     *
     * @return CollectionInterface
     */
    public function unserialize($data)
    {
        if ($this->checkValidCollection($data))
        {
            return $this->getCollectionFromArray($data);
        }
        throw new \InvalidArgumentException('Invalid Array Format for Collection');
    }

    /**
     * @param ItemInterface $item
     * @return array
     */
    protected function serializeItem(ItemInterface $item)
    {
        return $this->getArrayFromItem($item);
    }

    /**
     * @param CollectionInterface $collection
     * @return array
     */
    protected function getArrayFromCollection(CollectionInterface $collection)
    {
        $data = [
            static::CURRENCY => $collection->currency()->getCode(),
            static::AGGREGATE => $this->serializeItem($collection),
            static::ITEMS => []
        ];
        foreach ($collection->itemTypes() as $conceptName)
        {
            $item = $collection->find($conceptName);
            $data[static::ITEMS][$conceptName] = $item instanceof CollectionInterface ?
                $this->getArrayFromCollection($item) :
                $this->serializeItem($item);
        }
        return $data;
    }

    /**
     * @param ItemInterface $item
     * @return array
     */
    protected function getArrayFromItem(ItemInterface $item)
    {
        return [
            static::VAT => $this->moneyFormatter->format($item->vat()),
            static::NET => $this->moneyFormatter->format($item->net()),
            static::GROSS => $this->moneyFormatter->format($item->gross())
        ];
    }

    /**
     * @param string $currency
     * @param array $item
     * @return ItemInterface
     */
    protected function unserializeItem($currency, array $item)
    {
        if($this->checkValidItem($item))
        {
            return $this->itemFactory->buildFromBasicTypes($currency, $item[static::NET], $item[static::VAT], $item[static::GROSS]);
        }
        throw new \InvalidArgumentException('Invalid Array Format for Item');
    }

    /**
     * @param $data
     * @return CollectionInterface
     */
    protected function getCollectionFromArray(array $data)
    {
        $aggregate = $this->unserializeItem($data[static::CURRENCY], $data[static::AGGREGATE]);
        $items = [];
        foreach ($data[static::ITEMS] as $conceptName => $item)
        {
            $items[$conceptName] = $this->checkValidCollection($item) ?
                $this->getCollectionFromArray($item) :
                $this->unserializeItem($data[static::CURRENCY], $item)
            ;
        }
        return $this->collectionFactory->build(new Currency($data[static::CURRENCY]), $aggregate, $items);
    }

    /**
     * @param array $item
     * @return bool
     */
    protected function checkValidItem(array $item)
    {
        return isset($item[static::NET]) && isset($item[static::VAT]) && isset($item[static::GROSS]);
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function checkValidCollection(array $data)
    {
        return isset($data[static::CURRENCY]) && isset($data[static::AGGREGATE]) && isset($data[static::ITEMS]);
    }
}