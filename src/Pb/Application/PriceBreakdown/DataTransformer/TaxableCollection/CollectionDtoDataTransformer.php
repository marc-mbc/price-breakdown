<?php

namespace Pb\Application\PriceBreakdown\DataTransformer\TaxableCollection;

use Money\Currency;
use Pb\Application\PriceBreakdown\DataTransformer\TaxableItem\ItemDtoDataTransformerInterface;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;

/**
 * Class CollectionDtoDataTransformer
 * @package Pb\Application\PriceBreakdown\DataTransformer\TaxableCollection
 */
class CollectionDtoDataTransformer implements CollectionDtoDataTransformerInterface
{
    const AGGREGATE = 'aggregate';
    const ITEMS = 'items';
    const CURRENCY = 'currency';


    /**
     * @var TaxableCollectionFactory
     */
    protected $taxableCollectionFactory;

    /**
     * @var ItemDtoDataTransformerInterface
     */
    protected $itemDtoDataTransformer;

    /**
     * ArraySerializer constructor.
     * @param TaxableCollectionFactory $taxableCollectionFactory
     * @param ItemDtoDataTransformerInterface $itemDtoDataTransformer
     */
    public function __construct(
        TaxableCollectionFactory $taxableCollectionFactory,
        ItemDtoDataTransformerInterface $itemDtoDataTransformer
    )
    {
        $this->taxableCollectionFactory = $taxableCollectionFactory;
        $this->itemDtoDataTransformer = $itemDtoDataTransformer;
    }


    /**
     * @param TaxableCollection $domainObject
     * @return mixed
     */
    public function transformToDto(TaxableCollection $domainObject)
    {
        return $this->getArrayFromCollection($domainObject);
    }

    /**
     * @param mixed $dto
     * @return TaxableCollection
     */
    public function transformToDomain($dto)
    {
        if ($this->checkValidCollection($dto))
        {
            return $this->getCollectionFromArray($dto);
        }
        throw new \InvalidArgumentException('Invalid Array Format for TaxableCollection');
    }

    /**
     * @param TaxableCollection $taxableCollection
     * @return array
     */
    protected function getArrayFromCollection(TaxableCollection $taxableCollection)
    {
        $data = [
            static::CURRENCY => $taxableCollection->currency()->getCode(),
            static::AGGREGATE => $this->itemDtoDataTransformer->transformToDto($taxableCollection->aggregate()),
            static::ITEMS => []
        ];
        foreach ($taxableCollection->itemTypes() as $conceptName)
        {
            $taxableItem = $taxableCollection->find($conceptName);
            $data[static::ITEMS][$conceptName] = $taxableItem instanceof TaxableCollection ?
                $this->getArrayFromCollection($taxableItem) :
                $this->itemDtoDataTransformer->transformToDto($taxableItem);
        }
        return $data;
    }

    /**
     * @param $data
     * @return TaxableCollection
     */
    protected function getCollectionFromArray(array $data)
    {
        $aggregate = $this->itemDtoDataTransformer->transformToDomain($data[static::AGGREGATE]);
        $TaxableItems = [];
        foreach ($data[static::ITEMS] as $conceptName => $taxableItem)
        {
            $TaxableItems[$conceptName] = $this->checkValidCollection($taxableItem) ?
                $this->getCollectionFromArray($taxableItem) :
                $this->itemDtoDataTransformer->transformToDomain($taxableItem);
        }
        return $this->taxableCollectionFactory->build(new Currency($data[static::CURRENCY]), $aggregate, $TaxableItems);
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