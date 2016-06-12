<?php

namespace Pb\Test\Domain\PricingConcept\Service\Serializer;

use Money\Currency;
use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\ItemInterface;
use Pb\Domain\PricingConcept\Service\Serializer\ArraySerializer;
use Pb\Test\Domain\PricingConcept\PricingConceptTestHelper;

/**
 * Class ArraySerializerTest
 * @package Pb\Test\Domain\PricingConcept\Service\Serializer
 */
class ArraySerializerTest extends PricingConceptTestHelper
{
    const SERIALIZE = 'serialize';
    const UNSERIALIZE = 'unserialize';

    /**
     * @dataProvider getSerializationCases
     * @param string $operation
     * @param array|CollectionInterface $expected
     * @param array|CollectionInterface $source
     */
    public function testSerializationCases($operation, $expected, $source)
    {
        $serializer = $this->getSerializer();
        $this->assertEquals($expected, $serializer->{$operation}($source));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getInvalidArrayCases
     * @param array $source
     */
    public function testInvalidSerializationCases(array $source)
    {
        $serializer = $this->getSerializer();
        $serializer->unserialize($source);
    }

    public function getSerializationCases()
    {
        $currencyCode = 'EUR';
        $net = 100.25;
        $vat = 20.25;
        $gross = 120.5;
        $arrayItem = $this->getArrayItem($net, $vat, $gross);
        $item = $this->getItemFactory()->buildFromBasicTypes($currencyCode, $net, $vat, $gross);
        $conceptName = 'Margin';
        $simpleCollectionType = 'simpleCollection';

        $arrayFromEmptyCollection = $this->getArrayFromEmptyCollection($currencyCode);
        $emptyCollection = $this->getEmptyCollection($currencyCode);

        $arrayFromSimpleCollection = $this->getArrayFromSimpleCollection($currencyCode, $arrayItem, $conceptName);
        $simpleCollection = $this->getSimpleCollection($currencyCode, $item, $conceptName);

        $arrayFromNestedCollection = $this->getArrayFromNestedCollection(
            $currencyCode, $arrayItem, $conceptName, $simpleCollectionType, $arrayFromSimpleCollection
        );
        $nestedCollection = $this->getNestedCollection(
            $currencyCode, $item, $conceptName, $simpleCollectionType, $simpleCollection
        );

        return [
            'empty_case_serialize' => [
                static::SERIALIZE,
                $arrayFromEmptyCollection,
                $emptyCollection
            ],
            'empty_case_unserialize' => [
                static::UNSERIALIZE,
                $emptyCollection,
                $arrayFromEmptyCollection,
            ],
            'simple_case_serialize' => [
                static::SERIALIZE,
                $arrayFromSimpleCollection,
                $simpleCollection
            ],
            'simple_case_unserialize' => [
                static::UNSERIALIZE,
                $simpleCollection,
                $arrayFromSimpleCollection,
            ],
            'nested_case_serialize' => [
                static::SERIALIZE,
                $arrayFromNestedCollection,
                $nestedCollection
            ],
            'nested_case_unserialize' => [
                static::UNSERIALIZE,
                $nestedCollection,
                $arrayFromNestedCollection
            ]
        ];
    }

    public function getInvalidArrayCases()
    {
        $currencyCode = 'EUR';
        $netA = 100.25;
        $vatA = 20.25;
        $grossA = 120.5;
        $arrayItem = $this->getArrayItem($netA, $vatA, $grossA);
        $conceptName = 'Margin';
        $simpleCollectionType = 'simpleCollection';

        $arrayFromSimpleCollection = $this->getArrayFromSimpleCollection($currencyCode, $arrayItem, $conceptName);

        $arrayFromNestedCollection = $this->getArrayFromNestedCollection(
            $currencyCode, $arrayItem, $conceptName, $simpleCollectionType, $arrayFromSimpleCollection
        );

        return [
            'collection_without_aggregate' => [
                $this->removeKeyFromCollection($arrayFromSimpleCollection, ArraySerializer::AGGREGATE),
            ],
            'collection_without_currency' => [
                $this->removeKeyFromCollection($arrayFromSimpleCollection, ArraySerializer::CURRENCY),
            ],
            'collection_without_items' => [
                $this->removeKeyFromCollection($arrayFromSimpleCollection, ArraySerializer::ITEMS),
            ],
            'aggregate_without_gross' => [
                $this->removeKeyFromAggregate($arrayFromSimpleCollection, ArraySerializer::GROSS),
            ],
            'aggregate_without_net' => [
                $this->removeKeyFromAggregate($arrayFromSimpleCollection, ArraySerializer::NET),
            ],
            'aggregate_without_vat' => [
                $this->removeKeyFromAggregate($arrayFromSimpleCollection, ArraySerializer::NET),
            ],
            'item_without_gross' => [
                $this->removeKeyFromItem($arrayFromSimpleCollection, $conceptName, ArraySerializer::GROSS),
            ],
            'item_without_net' => [
                $this->removeKeyFromItem($arrayFromSimpleCollection, $conceptName, ArraySerializer::NET),
            ],
            'item_without_vat' => [
                $this->removeKeyFromItem($arrayFromSimpleCollection, $conceptName, ArraySerializer::VAT),
            ],
            'nested_collection_without_aggregate' => [
                $this->removeKeyFromNestedCollection($arrayFromNestedCollection, $simpleCollectionType, ArraySerializer::AGGREGATE),
            ],
            'nested_collection_without_currency' => [
                $this->removeKeyFromNestedCollection($arrayFromNestedCollection, $simpleCollectionType, ArraySerializer::CURRENCY),
            ],
            'nested_collection_without_items' => [
                $this->removeKeyFromNestedCollection($arrayFromNestedCollection, $simpleCollectionType, ArraySerializer::ITEMS),
            ],
            'nested_aggregate_without_gross' => [
                $this->removeKeyFromNestedAggregate($arrayFromNestedCollection, $simpleCollectionType, ArraySerializer::GROSS),
            ],
            'nested_aggregate_without_net' => [
                $this->removeKeyFromNestedAggregate($arrayFromNestedCollection, $simpleCollectionType, ArraySerializer::NET),
            ],
            'nested_aggregate_without_vat' => [
                $this->removeKeyFromNestedAggregate($arrayFromNestedCollection, $simpleCollectionType, ArraySerializer::NET),
            ],
            'nested_item_without_gross' => [
                $this->removeKeyFromNestedItem($arrayFromNestedCollection, $simpleCollectionType, $conceptName, ArraySerializer::GROSS),
            ],
            'nested_item_without_net' => [
                $this->removeKeyFromNestedItem($arrayFromNestedCollection, $simpleCollectionType, $conceptName, ArraySerializer::NET),
            ],
            'nested_item_without_vat' => [
                $this->removeKeyFromNestedItem($arrayFromNestedCollection, $simpleCollectionType, $conceptName, ArraySerializer::VAT),
            ]
        ];
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
            ArraySerializer::CURRENCY => $currencyCode,
            ArraySerializer::AGGREGATE => $aggregate,
            ArraySerializer::ITEMS => $items
        ];
    }

    /**
     * @param float|int $net
     * @param float|int $vat
     * @param float|int $gross
     * @return array
     */
    protected function getArrayItem($net, $vat, $gross)
    {
        return [
            ArraySerializer::NET => $net,
            ArraySerializer::VAT => $vat,
            ArraySerializer::GROSS => $gross
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
            $this->getArrayItem(0, 0, 0)
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
     * @return ArraySerializer
     */
    protected function getSerializer()
    {
        return new ArraySerializer($this->getCollectionFactory(), $this->getItemFactory(), $this->getMoneyFormatter());
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
     * @param array $arrayFromCollection
     * @param string $key
     * @return array
     */
    protected function removeKeyFromAggregate($arrayFromCollection, $key)
    {
        unset($arrayFromCollection[ArraySerializer::AGGREGATE][$key]);
        return $arrayFromCollection;
    }

    /**
     * @param array $arrayFromCollection
     * @param string $conceptNameItem
     * @param string $key
     * @return array
     */
    protected function removeKeyFromItem($arrayFromCollection, $conceptNameItem, $key)
    {
        unset($arrayFromCollection[ArraySerializer::ITEMS][$conceptNameItem][$key]);
        return $arrayFromCollection;
    }

    /**
     * @param array $arrayFromCollection
     * @param string $conceptNameCollection
     * @param string $key
     * @return array
     */
    protected function removeKeyFromNestedCollection(array $arrayFromCollection, $conceptNameCollection, $key)
    {
        unset($arrayFromCollection[ArraySerializer::ITEMS][$conceptNameCollection][$key]);
        return $arrayFromCollection;
    }

    /**
     * @param array $arrayFromCollection
     * @param string $conceptNameCollection
     * @param string $key
     * @return array
     */
    protected function removeKeyFromNestedAggregate(array $arrayFromCollection, $conceptNameCollection, $key)
    {
        unset($arrayFromCollection[ArraySerializer::ITEMS][$conceptNameCollection][ArraySerializer::AGGREGATE][$key]);
        return $arrayFromCollection;
    }

    /**
     * @param array $arrayFromCollection
     * @param string $conceptNameCollection
     * @param string $conceptNameItem
     * @param string $key
     * @return array
     */
    protected function removeKeyFromNestedItem(array $arrayFromCollection, $conceptNameCollection, $conceptNameItem, $key)
    {
        unset($arrayFromCollection[ArraySerializer::ITEMS][$conceptNameCollection][ArraySerializer::ITEMS][$conceptNameItem][$key]);
        return $arrayFromCollection;
    }
}
