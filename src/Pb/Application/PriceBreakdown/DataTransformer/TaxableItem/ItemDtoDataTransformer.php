<?php

namespace Pb\Application\PriceBreakdown\DataTransformer\TaxableItem;

use Money\MoneyFormatter;
use Pb\Domain\PriceBreakdown\Taxable;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;
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
     * @return TaxableCollectionFactory
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
     * @param Taxable $taxableItem
     * @return array
     */
    protected function getArrayFromItem(Taxable $taxableItem)
    {
        return [
            static::CURRENCY => $taxableItem->gross()->getCurrency()->getCode(),
            static::VAT => $this->moneyFormatter->format($taxableItem->vat()),
            static::NET => $this->moneyFormatter->format($taxableItem->net()),
            static::GROSS => $this->moneyFormatter->format($taxableItem->gross())
        ];
    }

    /**
     * @param array $taxableItem
     * @return Taxable
     */
    protected function getItemFromArray(array $taxableItem)
    {
        return $this->taxableItemFactory->buildFromBasicTypes(
            $taxableItem[static::CURRENCY], $taxableItem[static::NET], $taxableItem[static::VAT], $taxableItem[static::GROSS]
        );
    }

    /**
     * @param array $taxableItem
     * @return bool
     */
    protected function checkValidItem(array $taxableItem)
    {
        return  isset($taxableItem[static::CURRENCY]) &&
                isset($taxableItem[static::NET]) &&
                isset($taxableItem[static::VAT]) &&
                isset($taxableItem[static::GROSS]);
    }
}