<?php

namespace Pb\Test\Domain\PriceBreakdown\Service\Serializer;

use Pb\Domain\PriceBreakdown\CollectionInterface;
use Pb\Domain\PriceBreakdown\Service\Serializer\JsonSerializer;

/**
 * Class JsonSerializerTest
 * @package Pb\Test\Domain\PriceBreakdown\Service\Serializer
 */
class JsonSerializerTest extends ArraySerializerTest
{
    /**
     * @dataProvider getSerializationCases
     * @param string $operation
     * @param array|CollectionInterface $expected
     * @param array|CollectionInterface $source
     */
    public function testSerializationCases($operation, $expected, $source)
    {
        $serializer = $this->getSerializer();
        $output = $serializer->{$operation}(static::UNSERIALIZE === $operation ? json_encode($source) : $source);
        $this->assertEquals($expected, static::SERIALIZE === $operation ? json_decode($output, true) : $output);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getInvalidArrayCases
     * @param array $source
     */
    public function testInvalidSerializationCases(array $source)
    {
        $serializer = $this->getSerializer();
        $serializer->unserialize(json_encode($source));
    }

    /**
     * @return JsonSerializer
     */
    protected function getSerializer()
    {
        return new JsonSerializer($this->getCollectionFactory(), $this->getItemFactory(), $this->getMoneyFormatter());
    }
}
