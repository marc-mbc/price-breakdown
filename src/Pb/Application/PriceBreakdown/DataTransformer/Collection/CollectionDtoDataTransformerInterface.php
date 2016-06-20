<?php

namespace Pb\Application\PriceBreakdown\DataTransformer\Collection;

use Pb\Domain\PriceBreakdown\CollectionInterface;

/**
 * Interface CollectionDtoDataTransformerInterface
 * @package Pb\Application\PriceBreakdown\DataTransformer\Collection
 */
interface CollectionDtoDataTransformerInterface
{
    /**
     * @param CollectionInterface $domainObject
     * @return mixed
     */
    public function transformToDto(CollectionInterface $domainObject);
    /**
     * @param mixed $dto
     * @return CollectionInterface
     */
    public function transformToDomain($dto);
}