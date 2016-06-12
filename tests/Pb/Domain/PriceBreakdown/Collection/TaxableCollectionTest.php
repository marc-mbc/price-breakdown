<?php
namespace Pb\Test\Domain\PriceBreakdown\Collection;

use Pb\Test\Domain\PriceBreakdown\PriceBreakdownTestHelper;

/**
 * Class TaxableCollectionTest
 * @package Pb\Test\Domain\PriceBreakdown\Collection
 */
class TaxableCollectionTest extends PriceBreakdownTestHelper
{
    const DOES_NOT_EXIST = null;

    public function testEmptyCollectionShouldBeZero()
    {
        $currency = 'EUR';
        $collection = $this->getCollectionFactory()->build($this->getCurrency($currency));
        $money = $this->getMoney(0, $currency);

        $this->assertEquals($money, $collection->gross());
        $this->assertEquals($money, $collection->net());
        $this->assertEquals($money, $collection->vat());
        $this->assertEquals($currency, $collection->currency());
    }

    public function testCollectionCanBeBuiltWithPreCalculatedAggregate()
    {
        $currencyCode = 'EUR';
        $aggregate = $this->getItemFactory()->buildFromBasicTypes($currencyCode, 100, 1, 101);

        $currency = $this->getCurrency($currencyCode);
        $collection = $this->getCollectionFactory()->build($currency, $aggregate);

        $this->assertEquals($aggregate->gross(), $collection->gross());
        $this->assertEquals($aggregate->net(), $collection->net());
        $this->assertEquals($aggregate->vat(), $collection->vat());
        $this->assertEquals($currency, $collection->currency());
        $this->assertSame($aggregate, $collection->aggregate());
    }

    public function testCollectionCanBeBuiltWithItems()
    {
        $currency = 'EUR';
        $aggregate = $this->getItemFactory()->buildFromBasicTypes($currency, 100, 1, 101);
        $conceptNameItem1 = 'TestA';
        $items[$conceptNameItem1] = $this->getItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $conceptNameItem2 = 'TestB';
        $items[$conceptNameItem2] = $this->getItemFactory()->buildFromBasicTypes($currency, 101.25, 11.5);
        $conceptNameItem3 = 'TestC';
        $items[$conceptNameItem3] = $this->getItemFactory()->buildFromBasicTypes($currency, 50.25, 12.5);

        $collection = $this->getCollectionFactory()->build($this->getCurrency($currency), $aggregate, $items);

        $this->assertEquals($items[$conceptNameItem1], $collection->find($conceptNameItem1));
        $this->assertEquals($items[$conceptNameItem2], $collection->find($conceptNameItem2));
        $this->assertEquals($items[$conceptNameItem3], $collection->find($conceptNameItem3));
    }

    /**
     * @dataProvider getOperationTypes
     * @param String $operation
     */
    public function testCollectionShouldReturnHimselfOnEveryOperation($operation)
    {
        $currency = 'EUR';
        $collection = $this->getCollectionFactory()->build($this->getCurrency($currency));
        $this->assertSame(
            $collection, $collection->{$operation}('test', $this->getItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5))
        );
    }

    /**
     * @dataProvider getOperationTypes
     * @param String $operation
     */
    public function testCollectionShouldBeAbleToOperateWithNewTaxableItems($operation)
    {
        $currency = 'EUR';
        $collection = $this->getCollectionFactory()->build($this->getCurrency($currency));
        $emptyItem = $this->getItemFactory()->buildFromBasicTypes($currency, 0, 0, 0);
        $itemA = $this->getItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $itemB = $this->getItemFactory()->buildFromBasicTypes($currency, 101.25, 11.5);
        $itemC = $this->getItemFactory()->buildFromBasicTypes($currency, 101.25, 12.5);
        $collection->{$operation}('testA', $itemA);
        $collection->{$operation}('testB', $itemB);
        $collection->{$operation}('testC', $itemC);

        $this->assertEquals(
            $emptyItem->gross()->{$operation}(
                $itemA->gross()
            )->{$operation}(
                $itemB->gross()
            )->{$operation}(
               $itemC->gross()
            ),
            $collection->gross(),
            'Error operating with ' . $operation . ' calculating gross'
        );
        $this->assertEquals(
            $emptyItem->net()->{$operation}(
                $itemA->net()
            )->{$operation}(
                $itemB->net()
            )->{$operation}(
                $itemC->net()
            ),
            $collection->net(),
            'Error operating with ' . $operation . ' calculating net'
        );
        $this->assertEquals(
            $emptyItem->vat()->{$operation}(
                $itemA->vat()
            )->{$operation}(
                $itemB->vat()
            )->{$operation}(
                $itemC->vat()
            ),
            $collection->vat(),
            'Error operating with ' . $operation . ' calculating vat'
        );
    }

    /**
     * @dataProvider getOperationTypes
     * @param string $operation
     */
    public function testCollectionShouldBeAbleToFindAnyExistingItem($operation)
    {
        $currency = 'EUR';
        $collection = $this->getCollectionFactory()->build($this->getCurrency($currency));
        $item = $this->getItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $conceptName = 'testA';
        $collection->{$operation}($conceptName, $item);
        $this->assertEquals($item, $collection->find($conceptName));
    }

    /**
     * @dataProvider getOperationTypes
     * @param string $operation
     */
    public function testCollectionShouldNotFindANonExistentItems($operation)
    {
        $currency = 'EUR';
        $collection = $this->getCollectionFactory()->build($this->getCurrency($currency));
        $item = $this->getItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $collection->{$operation}('test', $item);
        $this->assertEquals(self::DOES_NOT_EXIST, $collection->find('non_existent'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getOperationTypes
     * @param string $operation
     */
    public function testCollectionShouldNotBeAbleToOperateWithSameItemTypeMoreThanOneTime($operation)
    {
        $currency = 'EUR';
        $collection = $this->getCollectionFactory()->build($this->getCurrency($currency));
        $collection->{$operation}('test', $this->getItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5));
        $collection->{$operation}('test', $this->getItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage New TaxableItem must operate in the same currency
     * @dataProvider getOperationTypes
     * @param string $operation
     */
    public function testCollectionShouldNotBeAbleToOperateWithItemsInDifferentCurrencies($operation)
    {
        $collection = $this->getCollectionFactory()->build($this->getCurrency('EUR'));
        $collection->{$operation}('test', $this->getItemFactory()->buildFromBasicTypes('USD', 100.25, 12.5));
    }

    /**
     * @dataProvider getOperationTypes
     * @param string $operation
     */
    public function testCollectionShouldBeAbleToOperateWithDifferentItemTypes($operation)
    {
        $currency = 'EUR';
        $collection = $this->getCollectionFactory()->build($this->getCurrency($currency));
        $item = $this->getItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $collection->{$operation}('testA', $item);
        $collection->{$operation}('testB', $this->getItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5));
        $collection->{$operation}('testC', $item);
    }

    /**
     * @dataProvider getOperationTypes
     * @param string $operation
     */
    public function testCollectionShouldBeAbleToReturnAllItemTypes($operation)
    {
        $currency = 'EUR';
        $item = $this->getItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $collection = $this->getCollectionFactory()->build($this->getCurrency($currency), $item, ['test' => $item]);
        $collection->{$operation}('testA', $item);
        $collection->{$operation}('testB', $item);
        $collection->{$operation}('testC', $item);
        $this->assertEquals(['test', 'testA', 'testB', 'testC'], $collection->itemTypes());
    }

    public function getOperationTypes()
    {
        return [
            'add_operator' => ['add'],
            'subtract_operator' => ['subtract']
        ];
    }

    public function testEmptyCollectionShouldNotFindAnyItem()
    {
        $currency = 'EUR';
        $collection = $this->getCollectionFactory()->build($this->getCurrency($currency));
        $this->assertEquals(self::DOES_NOT_EXIST, $collection->find('test'));
    }
}
