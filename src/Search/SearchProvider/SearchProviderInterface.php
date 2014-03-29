<?php

namespace Search\SearchProvider;

use Search\Option\SearchOptions;

interface SearchProviderInterface
{
    //should always return a Zend\Paginator
    public function search(SearchOptions $options);
}
