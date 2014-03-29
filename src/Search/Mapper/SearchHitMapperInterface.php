<?php

namespace Search\Mapper;

interface SearchHitMapperInterface
{
    //method should be able to take array of ids from hits and return models/etc
    public function mapHits(array $hitIds);
}
