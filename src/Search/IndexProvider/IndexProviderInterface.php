<?php

namespace Search\IndexProvider;

use Search\Option\IndexOptions;

interface IndexProviderInterface
{
    public function index(IndexOptions $options);
}
