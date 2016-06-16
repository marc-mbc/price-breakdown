<?php

namespace Pb\Application\PriceBreakdown\DataTransformer;

use Pb\Domain\PriceBreakdown\ItemInterface;

/**
 * Interface ItemDtoDataTransformerInterface
 * @package Pb\Application\PriceBreakdown\DataTransformer
 */
interface ItemDtoDataTransformerInterface
{
    /**
     * @param ItemInterface $domainObject
     * @return mixed
     */
    public function transformToDto(ItemInterface $domainObject);

    /**
     * @param mixed $dto
     * @return ItemInterface
     */
    public function transformToDomain($dto);
}