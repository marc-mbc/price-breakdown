<?php

namespace Pb\Application\PriceBreakdown\DataTransformer\TaxableItem;

use Pb\Domain\PriceBreakdown\Taxable;

/**
 * Interface ItemDtoDataTransformerInterface
 * @package Pb\Application\PriceBreakdown\DataTransformer\TaxableItem
 */
interface ItemDtoDataTransformerInterface
{
    /**
     * @param Taxable $domainObject
     * @return mixed
     */
    public function transformToDto(Taxable $domainObject);

    /**
     * @param mixed $dto
     * @return Taxable
     */
    public function transformToDomain($dto);
}