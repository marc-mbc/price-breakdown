<?php

namespace Pb\Application\PriceBreakdown\Assembler\Taxable;

use Pb\Domain\PriceBreakdown\Taxable;

/**
 * Interface TaxableAssembler
 * @package Pb\Application\PriceBreakdown\Assembler\Taxable
 */
interface TaxableAssembler
{
    /**
     * @param Taxable $object
     * @return mixed
     */
    public function assemble(Taxable $object);

    /**
     * @param mixed $data
     * @return Taxable
     */
    public function disassemble($data);
}