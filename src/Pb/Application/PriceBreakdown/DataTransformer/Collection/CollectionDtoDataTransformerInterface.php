<?php

namespace Pb\Application\PriceBreakdown\DataTransformer;

use Pb\Domain\PriceBreakdown\CollectionInterface;

/**
 * Interface CollectionDtoDataTransformerInterface
 * @package Pb\Application\PriceBreakdown\DataTransformer
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