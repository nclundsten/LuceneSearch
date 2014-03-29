<?php

namespace Search\Mapper;

use Search\Option\IndexOptions;

class ExampleMapper implements SearchHitMapperInterface, SearchIndexMapperInterface
{
    protected $data = array(
        1 => 'foooooooooooooo',
        2 => 'barrrrrrrrrrrrrrr',
        3 => 'bazzzzzzzzzzzzzzzz',
    );

    public function mapHits(array $ids)
    {
        $return = array();
        foreach ($ids as $id) {
            $return[$id] = $this->data[$id];
        }
        return $return;
    }

    public function getItemsForIndex(IndexOptions $options)
    {
        return $this->data;
    }
}
