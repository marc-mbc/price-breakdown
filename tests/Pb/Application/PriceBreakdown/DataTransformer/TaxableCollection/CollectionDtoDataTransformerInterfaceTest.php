<?php

namespace Pb\Test\Application\PriceBreakdown\DataTransformer\TaxableCollection;

use Money\Currency;
use Pb\Application\PriceBreakdown\DataTransformer\TaxableCollection\CollectionDtoDataTransformer;
use Pb\Application\PriceBreakdown\DataTransformer\TaxableItem\ItemDtoDataTransformer;
use Pb\Domain\PriceBreakdown\Taxable;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;
use Pb\Test\Application\PriceBreakdown\DataTransformer\TaxableItem\ItemDtoDataTransformerTest;

/**
 * Class CollectionDtoDataTransformerInterfaceTest
 * @package Pb\Test\Application\PriceBreakdown\DataTransformer\TaxableCollection
 */
class CollectionDtoDataTransformerInterfaceTest extends ItemDtoDataTransformerTest
{

    /**
     * @dataProvider getTransformToDtoCases
     * @param string $operation
     * @param array|Taxable $expected
     * @param array|Taxable $source
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
        $item = $this->getTaxableItemFactory()->buildFromBasicTypes($currencyCode, $net, $vat, $gross);

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
     * @return TaxableCollection
     */
    protected function getEmptyCollection($currencyCode)
    {
        return $this->getTaxableCollectionFactory()->build(
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
        return new CollectionDtoDataTransformer($this->getTaxableCollectionFactory(), $this->getItemDataTransformer());
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
     * @param Taxable $item
     * @param string $conceptName
     * @return TaxableCollection
     */
    protected function getSimpleCollection($currencyCode, Taxable $item, $conceptName)
    {
        return $this->getTaxableCollectionFactory()->build(
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
     * @param Taxable $item
     * @param string $conceptNameItem
     * @param string $conceptNameCollection
     * @param TaxableCollection $simpleCollection
     * @return TaxableCollection
     */
    protected function getNestedCollection(
        $currencyCode,
        Taxable $item,
        $conceptNameItem,
        $conceptNameCollection,
        TaxableCollection $simpleCollection
    )
    {
        return $this->getTaxableCollectionFactory()->build(
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
