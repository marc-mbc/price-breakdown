<?php
namespace Pb\Test\Domain\PriceBreakdown\Collection;

use Pb\Test\Domain\PriceBreakdown\PriceBreakdownTestHelper;

/**
 * Class TaxableCollectionTest
 * @package Pb\Test\Domain\PriceBreakdown\TaxableCollection
 */
class TaxableCollectionTest extends PriceBreakdownTestHelper
{
    const DOES_NOT_EXIST = null;

    public function testEmptyCollectionShouldBeZero()
    {
        $currency = 'EUR';
        $taxableCollection = $this->getTaxableCollectionFactory()->build($this->getCurrency($currency));
        $money = $this->getMoney(0, $currency);

        $this->assertEquals($money, $taxableCollection->gross());
        $this->assertEquals($money, $taxableCollection->net());
        $this->assertEquals($money, $taxableCollection->vat());
        $this->assertEquals($currency, $taxableCollection->currency());
    }

    public function testCollectionCanBeBuiltWithPreCalculatedAggregate()
    {
        $currencyCode = 'EUR';
        $aggregate = $this->getTaxableItemFactory()->buildFromBasicTypes($currencyCode, 100, 1, 101);

        $currency = $this->getCurrency($currencyCode);
        $taxableCollection = $this->getTaxableCollectionFactory()->build($currency, $aggregate);

        $this->assertEquals($aggregate->gross(), $taxableCollection->gross());
        $this->assertEquals($aggregate->net(), $taxableCollection->net());
        $this->assertEquals($aggregate->vat(), $taxableCollection->vat());
        $this->assertEquals($currency, $taxableCollection->currency());
        $this->assertSame($aggregate, $taxableCollection->aggregate());
    }

    public function testCollectionCanBeBuiltWithItems()
    {
        $currency = 'EUR';
        $aggregate = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 100, 1, 101);
        $conceptNameItem1 = 'TestA';
        $items[$conceptNameItem1] = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $conceptNameItem2 = 'TestB';
        $items[$conceptNameItem2] = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 101.25, 11.5);
        $conceptNameItem3 = 'TestC';
        $items[$conceptNameItem3] = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 50.25, 12.5);

        $taxableCollection = $this->getTaxableCollectionFactory()->build($this->getCurrency($currency), $aggregate, $items);

        $this->assertEquals($items[$conceptNameItem1], $taxableCollection->find($conceptNameItem1));
        $this->assertEquals($items[$conceptNameItem2], $taxableCollection->find($conceptNameItem2));
        $this->assertEquals($items[$conceptNameItem3], $taxableCollection->find($conceptNameItem3));
    }

    /**
     * @dataProvider getOperationTypes
     * @param String $operation
     */
    public function testCollectionShouldReturnHimselfOnEveryOperation($operation)
    {
        $currency = 'EUR';
        $taxableCollection = $this->getTaxableCollectionFactory()->build($this->getCurrency($currency));
        $this->assertSame(
            $taxableCollection, $taxableCollection->{$operation}('test', $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5))
        );
    }

    /**
     * @dataProvider getOperationTypes
     * @param String $operation
     */
    public function testCollectionShouldBeAbleToOperateWithNewTaxableItems($operation)
    {
        $currency = 'EUR';
        $moneyOperation = $operation === 'addUp' ? 'add' : $operation;
        $taxableCollection = $this->getTaxableCollectionFactory()->build($this->getCurrency($currency));

        $emptyItem = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 0, 0, 0);
        $itemA = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $itemB = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 101.25, 11.5);
        $itemC = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 101.25, 12.5);

        $taxableCollection->{$operation}('testA', $itemA);
        $taxableCollection->{$operation}('testB', $itemB);
        $taxableCollection->{$operation}('testC', $itemC);


        $this->assertEquals(
            $emptyItem->gross()->{$moneyOperation}(
                $itemA->gross()
            )->{$moneyOperation}(
                $itemB->gross()
            )->{$moneyOperation}(
               $itemC->gross()
            ),
            $taxableCollection->gross(),
            'Error operating with ' . $operation . ' calculating gross'
        );
        $this->assertEquals(
            $emptyItem->net()->{$moneyOperation}(
                $itemA->net()
            )->{$moneyOperation}(
                $itemB->net()
            )->{$moneyOperation}(
                $itemC->net()
            ),
            $taxableCollection->net(),
            'Error operating with ' . $operation . ' calculating net'
        );
        $this->assertEquals(
            $emptyItem->vat()->{$moneyOperation}(
                $itemA->vat()
            )->{$moneyOperation}(
                $itemB->vat()
            )->{$moneyOperation}(
                $itemC->vat()
            ),
            $taxableCollection->vat(),
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
        $taxableCollection = $this->getTaxableCollectionFactory()->build($this->getCurrency($currency));
        $item = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $conceptName = 'testA';
        $taxableCollection->{$operation}($conceptName, $item);
        $this->assertEquals($item, $taxableCollection->find($conceptName));
    }

    /**
     * @dataProvider getOperationTypes
     * @param string $operation
     */
    public function testCollectionShouldNotFindANonExistentItems($operation)
    {
        $currency = 'EUR';
        $taxableCollection = $this->getTaxableCollectionFactory()->build($this->getCurrency($currency));
        $item = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $taxableCollection->{$operation}('test', $item);
        $this->assertEquals(self::DOES_NOT_EXIST, $taxableCollection->find('non_existent'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getOperationTypes
     * @param string $operation
     */
    public function testCollectionShouldNotBeAbleToOperateWithSameItemTypeMoreThanOneTime($operation)
    {
        $currency = 'EUR';
        $taxableCollection = $this->getTaxableCollectionFactory()->build($this->getCurrency($currency));
        $taxableCollection->{$operation}('test', $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5));
        $taxableCollection->{$operation}('test', $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage New TaxableItem must operate in the same currency
     * @dataProvider getOperationTypes
     * @param string $operation
     */
    public function testCollectionShouldNotBeAbleToOperateWithItemsInDifferentCurrencies($operation)
    {
        $taxableCollection = $this->getTaxableCollectionFactory()->build($this->getCurrency('EUR'));
        $taxableCollection->{$operation}('test', $this->getTaxableItemFactory()->buildFromBasicTypes('USD', 100.25, 12.5));
    }

    /**
     * @dataProvider getOperationTypes
     * @param string $operation
     */
    public function testCollectionShouldBeAbleToOperateWithDifferentItemTypes($operation)
    {
        $currency = 'EUR';
        $taxableCollection = $this->getTaxableCollectionFactory()->build($this->getCurrency($currency));
        $item = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $taxableCollection->{$operation}('testA', $item);
        $taxableCollection->{$operation}('testB', $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5));
        $taxableCollection->{$operation}('testC', $item);
    }

    /**
     * @dataProvider getOperationTypes
     * @param string $operation
     */
    public function testCollectionShouldBeAbleToReturnAllItemTypes($operation)
    {
        $currency = 'EUR';
        $item = $this->getTaxableItemFactory()->buildFromBasicTypes($currency, 100.25, 12.5);
        $taxableCollection = $this->getTaxableCollectionFactory()->build($this->getCurrency($currency), $item, ['test' => $item]);
        $taxableCollection->{$operation}('testA', $item);
        $taxableCollection->{$operation}('testB', $item);
        $taxableCollection->{$operation}('testC', $item);
        $this->assertEquals(['test', 'testA', 'testB', 'testC'], $taxableCollection->itemTypes());
    }

    public function getOperationTypes()
    {
        return [
            'add_operator' => ['addUp'],
            'subtract_operator' => ['subtract']
        ];
    }

    public function testEmptyCollectionShouldNotFindAnyItem()
    {
        $currency = 'EUR';
        $taxableCollection = $this->getTaxableCollectionFactory()->build($this->getCurrency($currency));
        $this->assertEquals(self::DOES_NOT_EXIST, $taxableCollection->find('test'));
    }
}
