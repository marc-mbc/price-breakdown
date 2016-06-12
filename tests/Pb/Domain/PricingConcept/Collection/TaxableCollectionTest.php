<?php
namespace Pb\Test\Domain\PricingConcept\Collection;

use Pb\Test\Domain\PricingConcept\PricingConceptTestHelper;

/**
 * Class TaxableCollectionTest
 * @package Pb\Test\Domain\PricingConcept\Collection
 */
class TaxableCollectionTest extends PricingConceptTestHelper
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
        $item1 = 'TestA';
        $items[$item1] = $this->getItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $item2 = 'TestB';
        $items[$item2] = $this->getItemFactory()->buildFromBasicTypes($currency, 101.25, 11.5);
        $item3 = 'TestC';
        $items[$item3] = $this->getItemFactory()->buildFromBasicTypes($currency, 50.25, 12.5);

        $collection = $this->getCollectionFactory()->build($this->getCurrency($currency), $aggregate, $items);

        $this->assertEquals($items[$item1], $collection->find($item1));
        $this->assertEquals($items[$item2], $collection->find($item2));
        $this->assertEquals($items[$item3], $collection->find($item3));
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
            $emptyItem->gross()->{$operation}($itemA->gross())->{$operation}($itemB->gross())->{$operation}($itemC->gross()), $collection->gross(),
            'Error operating with ' . $operation . ' calculating gross'
        );
        $this->assertEquals(
            $emptyItem->net()->{$operation}($itemA->net())->{$operation}($itemB->net())->{$operation}($itemC->net()), $collection->net(),
            'Error operating with ' . $operation . ' calculating net'
        );
        $this->assertEquals(
            $emptyItem->vat()->{$operation}($itemA->vat())->{$operation}($itemB->vat())->{$operation}($itemC->vat()), $collection->vat(),
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
        $type = 'testA';
        $collection->{$operation}($type, $item);
        $this->assertEquals($item, $collection->find($type));
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
