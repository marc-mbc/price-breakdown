<?php

namespace Pb\Application\PriceBreakdown\DataTransformer\TaxableCollection;

use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;

/**
 * Interface CollectionDtoDataTransformerInterface
 * @package Pb\Application\PriceBreakdown\DataTransformer\TaxableCollection
 */
interface CollectionDtoDataTransformerInterface
{
    /**
     * @param TaxableCollection $domainObject
     * @return mixed
     */
    public function transformToDto(TaxableCollection$domainObject);

    /**
     * @param mixed $dto
     * @return TaxableCollection
     */
    public function transformToDomain($dto);
}