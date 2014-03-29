<?php

namespace Search\IndexProvider;

use ZendSearch\Lucene;
use ZendSearch\Lucene\Document;
use Search\Mapper\SearchIndexMapperInterface;
use Search\Option\IndexOptions;

class DefaultLuceneProvider implements IndexProviderInterface
{
    protected $storageLocation;

    protected $mapper;

    protected $type = 'default';

    public function __construct($storageLocation, SearchIndexMapperInterface $mapper, $type = null)
    {
        $this->storageLocation = $storageLocation;
        $this->mapper = $mapper;
        if ($type) {
            $this->type = $type;
        }
    }

    public function index(IndexOptions $options)
    {
        $type = $this->getType();

        $location = $this->getStorageLocation();
        $index = Lucene\Lucene::create($location . '/search-index-' . $type);
        foreach ($this->getItemsForIndex($options) as $id => $item) {
            $index->addDocument($this->itemDocument($id, $item, $options));
        }

        $index->optimize();
    }

    public function itemDocument($id, $item, IndexOptions $options)
    {
        $content = (string) $item;
        $doc = new Lucene\Document;
        $doc->addField(Document\Field::UnIndexed('id', $id, 'utf-8'));
        $doc->addField(Document\Field::UnStored('content', $content, 'utf-8'));
        return $doc;
    }

    public function getItemsForIndex(IndexOptions $options)
    {
        return $this->getMapper()->getItemsForIndex($options);
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getStorageLocation()
    {
        return $this->storageLocation;
    }

    public function setStorageLocation($storageLocation)
    {
        $this->storageLocation = $storageLocation;
        return $this;
    }

    public function getMapper()
    {
        return $this->mapper;
    }

    public function setMapper(SearchIndexMapperInterface $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }
}
