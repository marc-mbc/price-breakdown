<?php

namespace Pb\Application\PriceBreakdown\Assembler\TaxableCollection;

use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;

/**
 * Interface TaxableCollectionAssembler
 * @package Pb\Application\PriceBreakdown\Assembler\TaxableCollection
 */
interface TaxableCollectionAssembler
{
    /**
     * @param TaxableCollection $object
     * @return mixed
     */
    public function assemble(TaxableCollection $object);

    /**
     * @param mixed $data
     * @return TaxableCollection
     */
    public function disassemble($data);
}