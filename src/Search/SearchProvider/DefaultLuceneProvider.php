<?php

namespace Search\SearchProvider;

use ZendSearch\Lucene;
use ZendSearch\Lucene\Document;
use Zend\Paginator\Paginator;
use Search\Option\SearchOptions;
use Search\Mapper\SearchHitMapperInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class DefaultLuceneProvider implements SearchProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    protected $type = 'default';

    protected $resultMapperAdapter = '\Search\Paginator\Adapter\DefaultResultMapperAdapter';

    protected $hitMapper;

    protected $storageLocation;

    public function __construct($storageLocation, SearchHitMapperInterface $hitMapper, $type = null)
    {
        $this->storageLocation = $storageLocation;
        $this->hitMapper = $hitMapper;
        if ($type) {
            $this->type = $type;
        }
    }

    public function search(SearchOptions $options)
    {
        $type = $this->getType();
        $index = Lucene\Lucene::open($this->getStorageLocation() . '/search-index-' . $type);
        $query = self::buildQuery($options->query());
        $hits  = $index->find($query);

        return $this->getResults($hits, $options);
    }

    public function getResults($hits, SearchOptions $opts)
    {
        $adapterName = $this->getResultMapperAdapter();
        $adapter = new $adapterName($hits, $this->getHitMapper());
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($opts->page());
        $paginator->setItemCountPerPage($opts->limit());
        return $paginator;
    }

    public static function buildQuery($query)
    {
        $newQuery = '';
        $query = str_replace('-',' ', $query);
        $newQuery .= '('.self::exact($query).')';

        if(self::wildcardAfterOne($query)){
            $newQuery .= ' OR ';
            $newQuery .= '('.self::wildcardAfterOne($query).')';
        }
        return $newQuery;
    }

    public static function exact($query)
    {
        $words = explode(' ', $query);
        $newQuery = '';
        foreach($words as $word){
            $newQuery .= "+{$word} ";
        }
        return trim($newQuery);
    }

    public static function wildcardAfterOne($query)
    {
        $words = explode(' ', $query);
        if(count($words) == 1){
            return "+{$query}*";
        } else {
            return false;
        }
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

    public function getResultMapperAdapter()
    {
        return $this->resultMapperAdapter;
    }

    public function setResultMapperAdapter($resultMapperAdapter)
    {
        $this->resultMapperAdapter = $resultMapperAdapter;
        return $this;
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
