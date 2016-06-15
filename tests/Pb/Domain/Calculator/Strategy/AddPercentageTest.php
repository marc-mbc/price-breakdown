<?php

namespace Pb\Test\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\Strategy\AddPercentage;
use Pb\Domain\Calculator\Strategy\Multiplier;
use Pb\Domain\Calculator\Strategy\MultiplierInterface;
use Pb\Domain\PriceBreakdown\CalculatorStrategyInterface;

/**
 * Class AddPercentageTest
 * @package Pb\Test\Domain\Calculator\Strategy
 */
class AddPercentageTest extends CalculatorStrategyTest
{
    public function testStrategyWorksAsExpected()
    {
        $itemFactory = $this->getItemFactory();
        $collectionFactory = $this->getCollectionFactory();
        $conceptName = 'basePrice';
        $multiplierType = 'extraFee';
        $currencyCode = 'EUR';
        $gross = $this->getMoney(120.24, $currencyCode);
        $multiplier = 0.5;
        $multiplierStrategy = $this->getMultiplier($multiplier);


        $expectedCollection = $this->getCollectionWithSingleItem(
            $collectionFactory,
            $itemFactory,
            $currencyCode,
            $conceptName,
            $gross
        );
        $expectedCollection->add(
            $multiplierType,
            $itemFactory->buildWithGross($gross->multiply($multiplierStrategy->multiplier()))
        );

        $strategy = $this->getStrategy($multiplierType, $multiplierStrategy);
        $strategy->setItemFactory($itemFactory);
        $strategy->setCollectionFactory($collectionFactory);
        $this->assertEquals(
            $expectedCollection,
            $strategy->apply(
                $this->getCollectionWithSingleItem(
                    $collectionFactory,
                    $itemFactory,
                    $currencyCode,
                    $conceptName,
                    $gross
                )
            )
        );
    }

    /**
     * @param string $conceptName
     * @param MultiplierInterface $multiplier
     * @return CalculatorStrategyInterface
     */
    protected function getStrategy($conceptName = 'default', MultiplierInterface $multiplier = null)
    {
        return new AddPercentage($conceptName, $multiplier === null ? $this->getMultiplier(0.5) : $multiplier);
    }

    /**
     * @param float|int $multiplier
     * @return Multiplier
     */
    protected function getMultiplier($multiplier)
    {
        return new Multiplier($multiplier);
    }
}
