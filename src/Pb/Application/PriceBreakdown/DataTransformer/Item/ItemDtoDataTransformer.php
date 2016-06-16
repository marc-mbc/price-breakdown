<?php

namespace Pb\Application\PriceBreakdown\DataTransformer;

use Money\MoneyFormatter;
use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\ItemFactoryInterface;
use Pb\Domain\PriceBreakdown\ItemInterface;

/**
 * Class ItemDtoDataTransformer
 * @package Pb\Application\PriceBreakdown\DataTransformer
 */
class ItemDtoDataTransformer implements ItemDtoDataTransformerInterface
{
    const GROSS = 'gross';
    const NET = 'net';
    const VAT = 'vat';
    const CURRENCY = 'currency';

    /**
     * @var MoneyFormatter
     */
    protected $moneyFormatter;

    /**
     * @var ItemFactoryInterface
     */
    protected $itemFactory;

    /**
     * ArraySerializer constructor.
     * @param ItemFactoryInterface $itemFactory
     * @param MoneyFormatter $moneyFormatter
     */
    public function __construct(
        ItemFactoryInterface $itemFactory,
        MoneyFormatter $moneyFormatter
    )
    {
        $this->itemFactory = $itemFactory;
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * @param ItemInterface $domainObject
     * @return mixed
     */
    public function transformToDto(ItemInterface $domainObject)
    {
        return $this->getArrayFromItem($domainObject);
    }

    /**
     * @param mixed $dto
     * @return CollectionInterface
     */
    public function transformToDomain($dto)
    {
        if ($this->checkValidItem($dto))
        {
            return $this->getItemFromArray($dto);
        }
        throw new \InvalidArgumentException('Invalid Array Format for Item');
    }

    /**
     * @param ItemInterface $item
     * @return array
     */
    protected function getArrayFromItem(ItemInterface $item)
    {
        return [
            static::CURRENCY => $item->gross()->getCurrency()->getCode(),
            static::VAT => $this->moneyFormatter->format($item->vat()),
            static::NET => $this->moneyFormatter->format($item->net()),
            static::GROSS => $this->moneyFormatter->format($item->gross())
        ];
    }

    /**
     * @param array $item
     * @return ItemInterface
     */
    protected function getItemFromArray(array $item)
    {
        return $this->itemFactory->buildFromBasicTypes(
            $item[static::CURRENCY], $item[static::NET], $item[static::VAT], $item[static::GROSS]
        );
    }

    /**
     * @param array $item
     * @return bool
     */
    protected function checkValidItem(array $item)
    {
        return  isset($item[static::CURRENCY]) &&
                isset($item[static::NET]) &&
                isset($item[static::VAT]) &&
                isset($item[static::GROSS]);
    }
}