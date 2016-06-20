<?php

namespace Pb\Application\PriceBreakdown\DataTransformer\Collection;

use Money\Currency;
use Pb\Domain\PriceBreakdown\CollectionFactoryInterface;
use Pb\Domain\PriceBreakdown\CollectionInterface;

/**
 * Class CollectionDtoDataTransformer
 * @package Pb\Application\PriceBreakdown\DataTransformer\Collection
 */
class CollectionDtoDataTransformer implements CollectionDtoDataTransformerInterface
{
    const AGGREGATE = 'aggregate';
    const ITEMS = 'items';
    const CURRENCY = 'currency';


    /**
     * @var CollectionFactoryInterface
     */
    protected $collectionFactory;

    /**
     * @var ItemDtoDataTransformerInterface
     */
    protected $itemDtoDataTransformer;

    /**
     * ArraySerializer constructor.
     * @param CollectionFactoryInterface $collectionFactory
     * @param ItemDtoDataTransformerInterface $itemDtoDataTransformer
     */
    public function __construct(
        CollectionFactoryInterface $collectionFactory,
        ItemDtoDataTransformerInterface $itemDtoDataTransformer
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->itemDtoDataTransformer = $itemDtoDataTransformer;
    }


    /**
     * @param CollectionInterface $domainObject
     * @return mixed
     */
    public function transformToDto(CollectionInterface $domainObject)
    {
        return $this->getArrayFromCollection($domainObject);
    }

    /**
     * @param mixed $dto
     * @return CollectionInterface
     */
    public function transformToDomain($dto)
    {
        if ($this->checkValidCollection($dto))
        {
            return $this->getCollectionFromArray($dto);
        }
        throw new \InvalidArgumentException('Invalid Array Format for Collection');
    }

    /**
     * @param CollectionInterface $collection
     * @return array
     */
    protected function getArrayFromCollection(CollectionInterface $collection)
    {
        $data = [
            static::CURRENCY => $collection->currency()->getCode(),
            static::AGGREGATE => $this->itemDtoDataTransformer->transformToDto($collection->aggregate()),
            static::ITEMS => []
        ];
        foreach ($collection->itemTypes() as $conceptName)
        {
            $item = $collection->find($conceptName);
            $data[static::ITEMS][$conceptName] = $item instanceof CollectionInterface ?
                $this->getArrayFromCollection($item) :
                $this->itemDtoDataTransformer->transformToDto($item);
        }
        return $data;
    }

    /**
     * @param $data
     * @return CollectionInterface
     */
    protected function getCollectionFromArray(array $data)
    {
        $aggregate = $this->itemDtoDataTransformer->transformToDomain($data[static::AGGREGATE]);
        $items = [];
        foreach ($data[static::ITEMS] as $conceptName => $item)
        {
            $items[$conceptName] = $this->checkValidCollection($item) ?
                $this->getCollectionFromArray($item) :
                $this->itemDtoDataTransformer->transformToDomain($item);
        }
        return $this->collectionFactory->build(new Currency($data[static::CURRENCY]), $aggregate, $items);
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