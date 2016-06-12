<?php

namespace Pb\Test\Domain\Calculator;

use Money\Money;
use Pb\Domain\Calculator\Calculator;
use Pb\Domain\Calculator\Strategy\AddFixedAmount;
use Pb\Domain\Calculator\Strategy\AddMultiplierIncrement;
use Pb\Domain\Calculator\Strategy\Multiplier;
use Pb\Domain\PricingConcept\CollectionFactoryInterface;
use Pb\Domain\PricingConcept\CollectionInterface;
use Pb\Domain\PricingConcept\ItemFactoryInterface;
use Pb\Domain\PricingConcept\PricingConceptInterface;

/**
 * Class CalculatorTest
 * @package Pb\Test\Domain\Calculator
 */
class CalculatorTest extends CalculatorTestHelper
{
    public function testCalculatorReturnsHimSelfWhenAddAPricingConcept()
    {
        $calculator = $this->getCalculator();
        $this->assertSame(
            $calculator,
            $calculator->addPricingConcept(new AddFixedAmount('test', $this->getMoney(12.25)))
        );
    }

    /**
     * @dataProvider getFactoryInjectionCases
     * @param PricingConceptInterface $strategy
     * @param ItemFactoryInterface $defaultItemFactory
     * @param CollectionFactoryInterface $defaultCollectionFactory
     * @param ItemFactoryInterface $customItemFactory
     * @param CollectionFactoryInterface $customCollectionFactory
     */
    public function testCalculatorShouldBeAbleToInjectFactoriesOnEveryPricingConceptOrUseTheirDefaultFactories(
        PricingConceptInterface $strategy,
        ItemFactoryInterface $defaultItemFactory = null, CollectionFactoryInterface $defaultCollectionFactory = null,
        ItemFactoryInterface $customItemFactory = null, CollectionFactoryInterface $customCollectionFactory = null
    )
    {
        $calculator = $this->getCalculator($defaultCollectionFactory, $defaultItemFactory);
        $calculator->addPricingConcept($strategy, $customCollectionFactory, $customItemFactory);

        $expectedCollectionFactory = $customCollectionFactory === null ?
            $defaultCollectionFactory :
            $customCollectionFactory;
        $expectedItemFactory = $customItemFactory === null ?
            $defaultItemFactory :
            $customItemFactory;

        $this->assertAttributeSame($expectedCollectionFactory, 'collectionFactory', $strategy);
        $this->assertAttributeSame($expectedItemFactory, 'itemFactory', $strategy);
    }

    public function getFactoryInjectionCases()
    {
        $collectionFactoryA = $this->getCollectionFactory();
        $collectionFactoryB = $this->getCollectionFactory();
        $itemFactoryA = $this->getItemFactory();
        $itemFactoryB = $this->getItemFactory();
        $strategy = new AddFixedAmount('test', $this->getMoney(100));
        return [
            [$strategy, $itemFactoryA, $collectionFactoryA, null, null],
            [$strategy, $itemFactoryA, $collectionFactoryA, $itemFactoryB, null],
            [$strategy, $itemFactoryA, $collectionFactoryA, null, $collectionFactoryB],
            [$strategy, $itemFactoryA, $collectionFactoryA, $itemFactoryB, $collectionFactoryB]
        ];
    }

    /**
     * @dataProvider getCalculatorCases
     * @param Money $expectedGross
     * @param Calculator $calculator
     * @param CollectionInterface $initialCollection
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

        $fixedAmountConcept = new AddFixedAmount('testFixedAmount', $gross);
        $multiplierConcept = new AddMultiplierIncrement('testMultiplier', new Multiplier($multiplier));

        $calculatorFixedAmountPlusMultiplier->addPricingConcept($fixedAmountConcept);
        $calculatorFixedAmountPlusMultiplier->addPricingConcept($multiplierConcept);

        $calculatorMultiplierPlusFixedAmount->addPricingConcept($multiplierConcept);
        $calculatorMultiplierPlusFixedAmount->addPricingConcept($fixedAmountConcept);

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
                    $this->getCalculator()->addPricingConcept($fixedAmountConcept),
                    $this->getEmptyCollection($currencyCode)
            ],
            'single_concept_from_non_empty_initial_collection' => [
                $gross->add($gross),
                $this->getCalculator()->addPricingConcept($fixedAmountConcept),
                $this->getEmptyCollection($currencyCode)->add(
                    'TestExtra',
                    $this->getItemFactory()->buildWithGross($gross)
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
