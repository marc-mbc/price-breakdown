<?php

namespace Pb\Test\Domain\Calculator;

use Pb\Test\Domain\PriceBreakdown\PriceBreakdownTestHelper;

/**
 * Class TaxApplicatorTest
 * @package Pb\Test\Domain\Calculator
 */
class TaxApplicatorTest extends PriceBreakdownTestHelper
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Vat to apply must be [0-1] float
     * @dataProvider getInvalidTaxes
     * @param float $taxToApply
     */
    public function testTaxApplicatorShouldReturnExceptionWithInvalidTaxToApply($taxToApply)
    {
        $this->getTaxApplicator($taxToApply)->netFromGross($this->getMoney(100));
    }

    public function getInvalidTaxes()
    {
        return [
            'with_vat_to_apply_lower_than_0' => [-0.99],
            'with_vat_to_apply_higher_than_1' => [1.01],
            'string_as_vat_to_apply' => ['invalid'],
            'out_of_range_numeric_string_as_vat_to_apply' => ['1.1'],
        ];
    }

    /**
     * @dataProvider getValidTaxes
     * @param float $taxToApply
     */
    public function testTaxApplicatorShouldReturnItemProperlySetUp($taxToApply)
    {
        $money = $this->getMoney(100);
        $taxApplicator = $this->getTaxApplicator($taxToApply);
        $this->assertEquals($money->divide(1 + $taxToApply), $taxApplicator->netFromGross($money), 'Net Calculation');
        $this->assertEquals($money->multiply($taxToApply), $taxApplicator->vatFromNet($money), 'Vat Calculation');
    }

    public function getValidTaxes()
    {
        return [
            'with_vat_to_apply' => [0.20],
            'without_vat_to_apply' => [0],
        ];
    }
}
