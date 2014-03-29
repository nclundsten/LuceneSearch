<?php

namespace Search\Mapper;

use Search\Option\IndexOptions;

interface SearchIndexMapperInterface
{
    public function getItemsForIndex(IndexOptions $options);
}
