<?php

namespace Pb\Test\Domain\PricingConcept\Service\Serializer;

use Money\Currency;
use Pb\Domain\PricingConcept\CollectionInterface;
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
        $currency = 'EUR';
        $netA = 100.25;
        $vatA = 20.25;
        $grossA = 120.5;
        $arrayItemA = $this->getArrayItem($netA, $vatA, $grossA);
        $itemA = $this->getItemFactory()->buildFromBasicTypes($currency, $netA, $vatA, $grossA);
        $itemAType = 'Margin';
        $simpleCollectionType = 'simpleCollection';

        $arrayFromEmptyCollection = $this->getArrayFromEmptyCollection($currency);
        $emptyCollection = $this->getEmptyCollection($currency);

        $arrayFromSimpleCollection = $this->getArrayFromSimpleCollection($currency, $arrayItemA, $itemAType);
        $simpleCollection = $this->getSimpleCollection($currency, $itemA, $itemAType);

        $arrayFromNestedCollection = $this->getArrayFromNestedCollection(
            $currency, $arrayItemA, $itemAType, $simpleCollectionType, $arrayFromSimpleCollection
        );
        $nestedCollection = $this->getNestedCollection(
            $currency, $itemA, $itemAType, $simpleCollectionType, $simpleCollection
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
        $currency = 'EUR';
        $netA = 100.25;
        $vatA = 20.25;
        $grossA = 120.5;
        $arrayItemA = $this->getArrayItem($netA, $vatA, $grossA);
        $itemAType = 'Margin';
        $simpleCollectionType = 'simpleCollection';

        $arrayFromSimpleCollection = $this->getArrayFromSimpleCollection($currency, $arrayItemA, $itemAType);

        $arrayFromNestedCollection = $this->getArrayFromNestedCollection(
            $currency, $arrayItemA, $itemAType, $simpleCollectionType, $arrayFromSimpleCollection
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
                $this->removeKeyFromItem($arrayFromSimpleCollection, $itemAType, ArraySerializer::GROSS),
            ],
            'item_without_net' => [
                $this->removeKeyFromItem($arrayFromSimpleCollection, $itemAType, ArraySerializer::NET),
            ],
            'item_without_vat' => [
                $this->removeKeyFromItem($arrayFromSimpleCollection, $itemAType, ArraySerializer::VAT),
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
                $this->removeKeyFromNestedItem($arrayFromNestedCollection, $simpleCollectionType, $itemAType, ArraySerializer::GROSS),
            ],
            'nested_item_without_net' => [
                $this->removeKeyFromNestedItem($arrayFromNestedCollection, $simpleCollectionType, $itemAType, ArraySerializer::NET),
            ],
            'nested_item_without_vat' => [
                $this->removeKeyFromNestedItem($arrayFromNestedCollection, $simpleCollectionType, $itemAType, ArraySerializer::VAT),
            ]
        ];
    }

    protected function getArrayFromCollection($currency, array $aggregate, array $items = [])
    {
        return [
            ArraySerializer::CURRENCY => $currency,
            ArraySerializer::AGGREGATE => $aggregate,
            ArraySerializer::ITEMS => $items
        ];
    }

    protected function getArrayItem($net, $vat, $gross)
    {
        return [
            ArraySerializer::NET => $net,
            ArraySerializer::VAT => $vat,
            ArraySerializer::GROSS => $gross
        ];
    }

    /**
     * @param $currency
     * @return array
     */
    protected function getArrayFromEmptyCollection($currency)
    {
        return $this->getArrayFromCollection(
            $currency,
            $this->getArrayItem(0, 0, 0)
        );
    }

    /**
     * @param $currency
     * @return CollectionInterface
     */
    protected function getEmptyCollection($currency)
    {
        return $this->getCollectionFactory()->build(
            new Currency($currency)
        );
    }

    /**
     * @param $currency
     * @param $arrayItemA
     * @param $itemAType
     * @return array
     */
    protected function getArrayFromSimpleCollection($currency, $arrayItemA, $itemAType)
    {
        return $this->getArrayFromCollection(
            $currency,
            $arrayItemA,
            [$itemAType => $arrayItemA]
        );
    }

    /**
     * @param $currency
     * @param $itemA
     * @param $itemAType
     * @return CollectionInterface
     */
    protected function getSimpleCollection($currency, $itemA, $itemAType)
    {
        return $this->getCollectionFactory()->build(
            new Currency($currency),
            $itemA,
            [$itemAType => $itemA]
        );
    }

    /**
     * @param $currency
     * @param $arrayItemA
     * @param $itemAType
     * @param $simpleCollectionType
     * @param $arrayFromSimpleCollection
     * @return array
     */
    protected function getArrayFromNestedCollection($currency, $arrayItemA, $itemAType, $simpleCollectionType, $arrayFromSimpleCollection)
    {
        return $this->getArrayFromCollection(
            $currency,
            $arrayItemA,
            [
                $itemAType => $arrayItemA,
                $simpleCollectionType => $arrayFromSimpleCollection
            ]
        );
    }

    /**
     * @param $currency
     * @param $itemA
     * @param $itemAType
     * @param $simpleCollectionType
     * @param $simpleCollection
     * @return CollectionInterface
     */
    protected function getNestedCollection($currency, $itemA, $itemAType, $simpleCollectionType, $simpleCollection)
    {
        return $this->getCollectionFactory()->build(
            new Currency($currency),
            $itemA,
            [
                $itemAType => $itemA,
                $simpleCollectionType => $simpleCollection
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
     * @param $arrayFromCollection
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
     * @param string $itemType
     * @param string $key
     * @return array
     */
    protected function removeKeyFromItem($arrayFromCollection, $itemType, $key)
    {
        unset($arrayFromCollection[ArraySerializer::ITEMS][$itemType][$key]);
        return $arrayFromCollection;
    }

    /**
     * @param array $arrayFromCollection
     * @param string $collectionType
     * @param string $key
     * @return array
     */
    protected function removeKeyFromNestedCollection(array $arrayFromCollection, $collectionType, $key)
    {
        unset($arrayFromCollection[ArraySerializer::ITEMS][$collectionType][$key]);
        return $arrayFromCollection;
    }

    /**
     * @param array $arrayFromCollection
     * @param string $collectionType
     * @param string $key
     * @return array
     */
    protected function removeKeyFromNestedAggregate(array $arrayFromCollection, $collectionType, $key)
    {
        unset($arrayFromCollection[ArraySerializer::ITEMS][$collectionType][ArraySerializer::AGGREGATE][$key]);
        return $arrayFromCollection;
    }

    /**
     * @param array $arrayFromCollection
     * @param string $collectionType
     * @param string $itemType
     * @param string $key
     * @return array
     */
    protected function removeKeyFromNestedItem(array $arrayFromCollection, $collectionType, $itemType, $key)
    {
        unset($arrayFromCollection[ArraySerializer::ITEMS][$collectionType][ArraySerializer::ITEMS][$itemType][$key]);
        return $arrayFromCollection;
    }
}
