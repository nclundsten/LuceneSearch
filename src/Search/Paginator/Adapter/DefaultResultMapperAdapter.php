<?php

namespace Search\Paginator\Adapter;

use Zend\Paginator\Adapter\ArrayAdapter;
use Search\Mapper\SearchHitMapperInterface;

class DefaultResultMapperAdapter extends ArrayAdapter
{
    protected $hitMapper;

    public function __construct($hits, $hitMapper)
    {
        parent::__construct($hits);
        $this->setHitMapper($hitMapper);
    }

    public function getItems($offset, $itemCountPerPage)
    {
        $items = parent::getItems($offset, $itemCountPerPage);

        $ids = array();
        foreach ($items as $item) {
            $ids[] = $item->getDocument()->getFieldValue('id');
        }

        return $this->getHitMapper()->mapHits($ids);
    }

    public function getHitMapper()
    {
        return $this->hitMapper;
    }

    public function setHitMapper(SearchHitMapperInterface $hitMapper)
    {
        $this->hitMapper = $hitMapper;
        return $this;
    }
}
