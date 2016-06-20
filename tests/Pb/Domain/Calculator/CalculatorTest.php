<?php

namespace Pb\Test\Domain\Calculator;

use Money\Money;
use Pb\Domain\Calculator\BasicCalculator;
use Pb\Domain\Calculator\Strategy\AddFixedAmount;
use Pb\Domain\Calculator\Strategy\AddPercentage;
use Pb\Domain\Calculator\Strategy\CalculatorStrategy;
use Pb\Domain\Calculator\Strategy\BasicMultiplier;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;
use Pb\Domain\PriceBreakdown\TaxableItem\TaxableItemFactory;

/**
 * Class CalculatorTest
 * @package Pb\Test\Domain\Calculator
 */
class CalculatorTest extends CalculatorTestHelper
{
    public function testCalculatorReturnsHimSelfWhenAddAPriceBreakdown()
    {
        $calculator = $this->getCalculator();
        $this->assertSame(
            $calculator,
            $calculator->addStrategy(new AddFixedAmount('test', $this->getMoney(12.25)))
        );
    }

    /**
     * @dataProvider getFactoryInjectionCases
     * @param CalculatorStrategy $strategy
     * @param TaxableItemFactory $defaultItemFactory
     * @param TaxableCollectionFactory $defaultCollectionFactory
     * @param TaxableItemFactory $customItemFactory
     * @param TaxableCollectionFactory $customCollectionFactory
     */
    public function testCalculatorShouldBeAbleToInjectFactoriesOnEveryPriceBreakdownOrUseTheirDefaultFactories(
        CalculatorStrategy $strategy,
        TaxableItemFactory $defaultItemFactory = null, TaxableCollectionFactory $defaultCollectionFactory = null,
        TaxableItemFactory $customItemFactory = null, TaxableCollectionFactory $customCollectionFactory = null
    )
    {
        $calculator = $this->getCalculator($defaultCollectionFactory, $defaultItemFactory);
        $calculator->addStrategy($strategy, $customCollectionFactory, $customItemFactory);

        $expectedCollectionFactory = $customCollectionFactory === null ?
            $defaultCollectionFactory :
            $customCollectionFactory;
        $expectedItemFactory = $customItemFactory === null ?
            $defaultItemFactory :
            $customItemFactory;

        $this->assertAttributeSame($expectedCollectionFactory, 'taxableCollectionFactory', $strategy);
        $this->assertAttributeSame($expectedItemFactory, 'taxableItemFactory', $strategy);
    }

    public function getFactoryInjectionCases()
    {
        $collectionFactoryA = $this->getTaxableCollectionFactory();
        $collectionFactoryB = $this->getTaxableCollectionFactory();
        $taxableItemFactoryA = $this->getTaxableItemFactory();
        $taxableItemFactoryB = $this->getTaxableItemFactory();
        $strategy = new AddFixedAmount('test', $this->getMoney(100));
        return [
            [$strategy, $taxableItemFactoryA, $collectionFactoryA, null, null],
            [$strategy, $taxableItemFactoryA, $collectionFactoryA, $taxableItemFactoryB, null],
            [$strategy, $taxableItemFactoryA, $collectionFactoryA, null, $collectionFactoryB],
            [$strategy, $taxableItemFactoryA, $collectionFactoryA, $taxableItemFactoryB, $collectionFactoryB]
        ];
    }

    /**
     * @dataProvider getCalculatorCases
     * @param Money $expectedGross
     * @param BasicCalculator $calculator
     * @param TaxableCollection $initialCollection
     */
    public function testCalculatorShouldCalculateProperly($expectedGross, $calculator, $initialCollection)
    {
        $this->assertEquals(
            $expectedGross,
            $calculator->calculate(
                $initialCollection
            )->gross()
        );
    }

    public function getCalculatorCases()
    {
        $calculatorFixedAmountPlusMultiplier = $this->getCalculator();
        $calculatorMultiplierPlusFixedAmount = $this->getCalculator();
        $currencyCode = 'EUR';
        $gross = $this->getMoney(12.25, $currencyCode);
        $multiplier = 2;

        $fixedAmountStrategy = new AddFixedAmount('testFixedAmount', $gross);
        $multiplierStrategy = new AddPercentage('testMultiplier', new BasicMultiplier($multiplier));

        $calculatorFixedAmountPlusMultiplier->addStrategy($fixedAmountStrategy);
        $calculatorFixedAmountPlusMultiplier->addStrategy($multiplierStrategy);

        $calculatorMultiplierPlusFixedAmount->addStrategy($multiplierStrategy);
        $calculatorMultiplierPlusFixedAmount->addStrategy($fixedAmountStrategy);

        $expectedGrossFixedAmountPlusMultiplier = $gross->add($gross->multiply($multiplier));
        $expectedGrossMultiplierPlusFixedAmount = $this->getMoney(0)->multiply($multiplier)->add($gross);

        return [
            'without_any_concept' => [
                    $this->getMoney(0),
                    $this->getCalculator(),
                    $this->getEmptyCollection($currencyCode)
            ],
            'single_concept' => [
                    $gross,
                    $this->getCalculator()->addStrategy($fixedAmountStrategy),
                    $this->getEmptyCollection($currencyCode)
            ],
            'single_concept_from_non_empty_initial_collection' => [
                $gross->add($gross),
                $this->getCalculator()->addStrategy($fixedAmountStrategy),
                $this->getEmptyCollection($currencyCode)->addUp(
                    'TestExtra',
                    $this->getTaxableItemFactory()->buildWithGross($gross)
                )
            ],
            'multiple_concepts' => [
                    $expectedGrossFixedAmountPlusMultiplier,
                    $calculatorFixedAmountPlusMultiplier,
                    $this->getEmptyCollection($currencyCode)
            ],
            'multiple_concepts_in_different_order' => [
                    $expectedGrossMultiplierPlusFixedAmount,
                    $calculatorMultiplierPlusFixedAmount,
                    $this->getEmptyCollection($currencyCode)
            ]
        ];
    }
}
