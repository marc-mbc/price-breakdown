<?php

namespace Pb\Application\PriceBreakdown\DataTransformer\TaxableItem;

use Money\MoneyFormatter;
use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\Taxable;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Class ItemDtoDataTransformer
 * @package Pb\Application\PriceBreakdown\DataTransformer\TaxableItem
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
     * @var TaxableItemFactory
     */
    protected $taxableItemFactory;

    /**
     * ArraySerializer constructor.
     * @param TaxableItemFactory $itemFactory
     * @param MoneyFormatter $moneyFormatter
     */
    public function __construct(
        TaxableItemFactory $itemFactory,
        MoneyFormatter $moneyFormatter
    )
    {
        $this->taxableItemFactory = $itemFactory;
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * @param Taxable $domainObject
     * @return mixed
     */
    public function transformToDto(Taxable $domainObject)
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
        throw new \InvalidArgumentException('Invalid Array Format for TaxableItem');
    }

    /**
     * @param Taxable $item
     * @return array
     */
    protected function getArrayFromItem(Taxable $item)
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
     * @return Taxable
     */
    protected function getItemFromArray(array $item)
    {
        return $this->taxableItemFactory->buildFromBasicTypes(
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