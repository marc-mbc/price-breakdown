<?php

namespace Pb\Test\Domain\Calculator\Strategy;

use Pb\Domain\Calculator\Strategy\Multiplier;

/**
 * Class MultiplierTest
 * @package Pb\Test\Domain\Calculator\Strategy
 */
class MultiplierTest extends \PHPUnit_Framework_TestCase
{
    public function testMultiplier()
    {
        $multiplier = 0.2;
        /** @var Multiplier $multiplierStrategy */
        $multiplierStrategy = new Multiplier($multiplier);
        $this->assertEquals($multiplier, $multiplierStrategy->multiplier());
    }
}
