<?php

namespace Pb\Application\PriceBreakdown\Assembler\TaxableCollection;

use Money\Currency;
use Pb\Application\PriceBreakdown\Assembler\Taxable\TaxableAssembler;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollection;
use Pb\Domain\PriceBreakdown\TaxableCollection\TaxableCollectionFactory;

/**
 * Class TaxableCollectionArrayAssembler
 * @package Pb\Application\PriceBreakdown\Assembler\TaxableCollection
 */
class TaxableCollectionArrayAssembler implements TaxableCollectionAssembler
{
    const AGGREGATE = 'aggregate';
    const ITEMS = 'items';
    const CURRENCY = 'currency';


    /**
     * @var TaxableCollectionFactory
     */
    protected $taxableCollectionFactory;

    /**
     * @var TaxableAssembler
     */
    protected $itemDtoDataTransformer;

    /**
     * ArraySerializer constructor.
     * @param TaxableCollectionFactory $taxableCollectionFactory
     * @param TaxableAssembler $itemDtoDataTransformer
     */
    public function __construct(
        TaxableCollectionFactory $taxableCollectionFactory,
        TaxableAssembler $itemDtoDataTransformer
    )
    {
        $this->taxableCollectionFactory = $taxableCollectionFactory;
        $this->itemDtoDataTransformer = $itemDtoDataTransformer;
    }


    /**
     * @param TaxableCollection $object
     * @return mixed
     */
    public function assemble(TaxableCollection $object)
    {
        return $this->getArrayFromCollection($object);
    }

    /**
     * @param mixed $data
     * @return TaxableCollection
     */
    public function disassemble($data)
    {
        if ($this->checkValidCollection($data))
        {
            return $this->getCollectionFromArray($data);
        }
        throw new \InvalidArgumentException('Invalid Array Format for TaxableCollection');
    }

    /**
     * @param TaxableCollection $taxableCollection
     * @return array
     */
    protected function getArrayFromCollection(TaxableCollection $taxableCollection)
    {
        $data = [
            static::CURRENCY => $taxableCollection->currency()->getCode(),
            static::AGGREGATE => $this->itemDtoDataTransformer->assemble($taxableCollection->aggregate()),
            static::ITEMS => []
        ];
        foreach ($taxableCollection->itemTypes() as $conceptName)
        {
            $taxableItem = $taxableCollection->find($conceptName);
            $data[static::ITEMS][$conceptName] = $taxableItem instanceof TaxableCollection ?
                $this->getArrayFromCollection($taxableItem) :
                $this->itemDtoDataTransformer->assemble($taxableItem);
        }
        return $data;
    }

    /**
     * @param $data
     * @return TaxableCollection
     */
    protected function getCollectionFromArray(array $data)
    {
        $aggregate = $this->itemDtoDataTransformer->disassemble($data[static::AGGREGATE]);
        $TaxableItems = [];
        foreach ($data[static::ITEMS] as $conceptName => $taxableItem)
        {
            $TaxableItems[$conceptName] = $this->checkValidCollection($taxableItem) ?
                $this->getCollectionFromArray($taxableItem) :
                $this->itemDtoDataTransformer->disassemble($taxableItem);
        }
        return $this->taxableCollectionFactory->build(new Currency($data[static::CURRENCY]), $aggregate, $TaxableItems);
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function checkValidCollection(array $data)
    {
        return isset($data[static::CURRENCY]) && isset($data[static::AGGREGATE]) && isset($data[static::ITEMS]);
    }
}