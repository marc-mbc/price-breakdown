<?php

namespace Pb\Test\Application\PriceBreakdown\DataTransformer;

use Money\Currency;
use Pb\Application\PriceBreakdown\DataTransformer\CollectionDtoDataTransformer;
use Pb\Application\PriceBreakdown\DataTransformer\ItemDtoDataTransformer;
use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\ItemInterface;

/**
 * Class CollectionDtoDataTransformerInterfaceTest
 * @package Pb\Test\Application\PriceBreakdown\DataTransformer
 */
class CollectionDtoDataTransformerInterfaceTest extends ItemDtoDataTransformerTest
{

    /**
     * @dataProvider getTransformToDtoCases
     * @param string $operation
     * @param array|ItemInterface $expected
     * @param array|ItemInterface $source
     */
    public function testTransformToDto($operation, $expected, $source)
    {
        $dataTransformer = $this->getDataTransformer();

        $this->assertEquals($expected, $dataTransformer->{$operation}($source));
    }

    public function getTransformToDtoCases()
    {
        $currencyCode = 'EUR';
        $net = 100.25;
        $vat = 20.25;
        $gross = 120.5;
        $conceptName = 'basePrice';
        $simpleCollectionConceptName = 'simpleCollection';

        $emptyCollection = $this->getEmptyCollection($currencyCode);
        $arrayFromEmptyCollection = $this->getArrayFromEmptyCollection($currencyCode);

        $arrayItem = $this->getArrayItem($currencyCode, $net, $vat, $gross);
        $item = $this->getItemFactory()->buildFromBasicTypes($currencyCode, $net, $vat, $gross);

        $arrayFromSimpleCollection = $this->getArrayFromSimpleCollection($currencyCode, $arrayItem, $conceptName);
        $simpleCollection = $this->getSimpleCollection($currencyCode, $item, $conceptName);

        $arrayFromNestedCollection = $this->getArrayFromNestedCollection(
            $currencyCode, $arrayItem, $conceptName, $simpleCollectionConceptName, $arrayFromSimpleCollection
        );
        $nestedCollection = $this->getNestedCollection(
            $currencyCode, $item, $conceptName, $simpleCollectionConceptName, $simpleCollection
        );

        return [
            'empty_case_transform_to_dto' => [
                static::TRANSFORM_TO_DTO,
                $arrayFromEmptyCollection,
                $emptyCollection
            ],
            'empty_case_transform_to_domain' => [
                static::TRANSFORM_TO_DOMAIN,
                $emptyCollection,
                $arrayFromEmptyCollection,
            ],
            'simple_case_transform_to_dto' => [
                static::TRANSFORM_TO_DTO,
                $arrayFromSimpleCollection,
                $simpleCollection
            ],
            'simple_case_transform_to_domain' => [
                static::TRANSFORM_TO_DOMAIN,
                $simpleCollection,
                $arrayFromSimpleCollection,
            ],
            'nested_case_transform_to_dto' => [
                static::TRANSFORM_TO_DTO,
                $arrayFromNestedCollection,
                $nestedCollection
            ],
            'nested_case_transform_to_domain' => [
                static::TRANSFORM_TO_DOMAIN,
                $nestedCollection,
                $arrayFromNestedCollection
            ]
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getInvalidDtoCases
     * @param array $source
     */
    public function testInvalidSerializationCases(array $source)
    {
        $dataTransformer = $this->getDataTransformer();

        $dataTransformer->transformToDomain($source);
    }

    public function getInvalidDtoCases()
    {
        $currencyCode = 'EUR';
        $arrayItem = $this->getArrayItem($currencyCode, 100.25, 20.25, 120.5);
        $conceptName = 'Margin';
        $simpleCollectionType = 'simpleCollection';

        return [
            'collection_without_aggregate' => [
                $this->removeKeyFromCollection(
                    $this->getArrayFromSimpleCollection($currencyCode, $arrayItem, $conceptName),
                    CollectionDtoDataTransformer::AGGREGATE
                ),
            ],
            'collection_without_currency' => [
                $this->removeKeyFromCollection(
                    $this->getArrayFromSimpleCollection($currencyCode, $arrayItem, $conceptName),
                    CollectionDtoDataTransformer::CURRENCY
                ),
            ],
            'collection_without_items' => [
                $this->removeKeyFromCollection(
                    $this->getArrayFromSimpleCollection($currencyCode, $arrayItem, $conceptName),
                    CollectionDtoDataTransformer::ITEMS
                ),
            ],
            'nested_collection_without_aggregate' => [
                $this->removeKeyFromNestedCollection(
                    $this->getArrayFromNestedCollection(
                        $currencyCode,
                        $arrayItem,
                        $conceptName,
                        $simpleCollectionType,
                        $this->getArrayFromSimpleCollection($currencyCode, $arrayItem, $conceptName)
                    ),
                    $simpleCollectionType,
                    CollectionDtoDataTransformer::AGGREGATE
                ),
            ],
            'nested_collection_without_currency' => [
                $this->removeKeyFromNestedCollection(
                    $this->getArrayFromNestedCollection(
                        $currencyCode,
                        $arrayItem,
                        $conceptName,
                        $simpleCollectionType,
                        $this->getArrayFromSimpleCollection($currencyCode, $arrayItem, $conceptName)
                    ),
                    $simpleCollectionType,
                    CollectionDtoDataTransformer::CURRENCY
                ),
            ],
            'nested_collection_without_items' => [
                $this->removeKeyFromNestedCollection(
                    $this->getArrayFromNestedCollection(
                        $currencyCode,
                        $arrayItem,
                        $conceptName,
                        $simpleCollectionType,
                        $this->getArrayFromSimpleCollection($currencyCode, $arrayItem, $conceptName)
                    ),
                    $simpleCollectionType,
                    CollectionDtoDataTransformer::ITEMS
                ),
            ]
        ];
    }

    /**
     * @param string $currencyCode
     * @return array
     */
    protected function getArrayFromEmptyCollection($currencyCode)
    {
        return $this->getArrayFromCollection(
            $currencyCode,
            $this->getArrayItem($currencyCode, 0, 0, 0)
        );
    }

    /**
     * @param string $currencyCode
     * @return CollectionInterface
     */
    protected function getEmptyCollection($currencyCode)
    {
        return $this->getCollectionFactory()->build(
            new Currency($currencyCode)
        );
    }

    /**
     * @param string $currencyCode
     * @param array $aggregate
     * @param array $items
     * @return array
     */
    protected function getArrayFromCollection($currencyCode, array $aggregate, array $items = [])
    {
        return [
            CollectionDtoDataTransformer::CURRENCY => $currencyCode,
            CollectionDtoDataTransformer::AGGREGATE => $aggregate,
            CollectionDtoDataTransformer::ITEMS => $items
        ];
    }

    /**
     * @param array $arrayFromCollection
     * @param string $key
     * @return array
     */
    protected function removeKeyFromCollection(array $arrayFromCollection, $key)
    {
        unset($arrayFromCollection[$key]);
        return $arrayFromCollection;
    }

    /**
     * @return CollectionDtoDataTransformer
     */
    protected function getDataTransformer()
    {
        return new CollectionDtoDataTransformer($this->getCollectionFactory(), $this->getItemDataTransformer());
    }

    /**
     * @return ItemDtoDataTransformer
     */
    protected function getItemDataTransformer()
    {
        return parent::getDataTransformer();
    }
    /**
     * @param string $currencyCode
     * @param array $arrayItem
     * @param string $conceptName
     * @return array
     */
    protected function getArrayFromSimpleCollection($currencyCode, array $arrayItem, $conceptName)
    {
        return $this->getArrayFromCollection(
            $currencyCode,
            $arrayItem,
            [$conceptName => $arrayItem]
        );
    }

    /**
     * @param string $currencyCode
     * @param ItemInterface $item
     * @param string $conceptName
     * @return CollectionInterface
     */
    protected function getSimpleCollection($currencyCode, ItemInterface $item, $conceptName)
    {
        return $this->getCollectionFactory()->build(
            new Currency($currencyCode),
            $item,
            [$conceptName => $item]
        );
    }

    /**
     * @param string $currencyCode
     * @param array $arrayItem
     * @param string $conceptNameItem
     * @param string $conceptNameCollection
     * @param array $arrayFromSimpleCollection
     * @return array
     */
    protected function getArrayFromNestedCollection(
        $currencyCode, array $arrayItem, $conceptNameItem, $conceptNameCollection, array $arrayFromSimpleCollection
    )
    {
        return $this->getArrayFromCollection(
            $currencyCode,
            $arrayItem,
            [
                $conceptNameItem => $arrayItem,
                $conceptNameCollection => $arrayFromSimpleCollection
            ]
        );
    }

    /**
     * @param string $currencyCode
     * @param ItemInterface $item
     * @param string $conceptNameItem
     * @param string $conceptNameCollection
     * @param CollectionInterface $simpleCollection
     * @return CollectionInterface
     */
    protected function getNestedCollection(
        $currencyCode,
        ItemInterface $item,
        $conceptNameItem,
        $conceptNameCollection,
        CollectionInterface $simpleCollection
    )
    {
        return $this->getCollectionFactory()->build(
            new Currency($currencyCode),
            $item,
            [
                $conceptNameItem => $item,
                $conceptNameCollection => $simpleCollection
            ]
        );
    }

    /**
     * @param array $arrayFromCollection
     * @param string $conceptNameCollection
     * @param string $key
     * @return array
     */
    protected function removeKeyFromNestedCollection(array $arrayFromCollection, $conceptNameCollection, $key)
    {
        unset($arrayFromCollection[CollectionDtoDataTransformer::ITEMS][$conceptNameCollection][$key]);
        return $arrayFromCollection;
    }
}
