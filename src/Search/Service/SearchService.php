<?php

namespace Search\Service;

use Search\SearchProvider\SearchProviderInterface;
use Search\Option\SearchOptions;

class SearchService
{
    protected $searchProviders = array();
    protected $defaultTypes = array('default'=>array());

    public function search($query, array $types=null)
    {
        $types = $types ?: $this->getDefaultTypes();

        $return = array();
        foreach ($types as $name => $options) {
            $options['query'] = $query;
            $return[$name] = $this->searchOne($name, $options);
        }

        return $return;
    }

    public function searchOne($name, $options=null)
    {
        $options = $this->getOptions($options);
        $results = $this->getSearchProvider($name)->search($options);
        return array(
            'options' => $options,
            'results' => $results,
        );
    }

    public function getOptions($options=null)
    {
        if (is_array($options)) {
            $options = new SearchOptions($options);
        } elseif ($options === null) {
            $options = new SearchOptions(array());
        } elseif (!$options instanceOf SearchOptions) {
            throw new \Exception('search options must be array, instance of search options, or null');
        }
        return $options;
    }

    public function getSearchProvider($name)
    {
        if (!is_string($name)) {
            throw new \Exception('search provider name must be string, got'. gettype($name));
        }
        if (!array_key_exists($name, $this->searchProviders)) {
            throw new \Exception('search provider not registered - ' . $name);
        }
        $provider = $this->searchProviders[$name];
        if (!$provider instanceOf SearchProviderInterface) {
            throw new \Exception('search provider must be instance of SearchProviderInterface');
        }
        return $provider;
    }

    public function getSearchProviders()
    {
        return $this->searchProviders;
    }

    public function addSearchProvider($name, SearchProviderInterface $provider)
    {
        $this->searchProviders[$name] = $provider;
        return $this;
    }

    public function setSearchProviders($searchProviders)
    {
        $this->searchProviders = array();
        foreach ($searchProviders as $name => $provider) {
            $this->addSearchProvider($name, $provider);
        }
        return $this;
    }

    public function getDefaultTypes()
    {
        return $this->defaultTypes;
    }

    public function setDefaultTypes($defaultTypes)
    {
        $this->defaultTypes = $defaultTypes;
        return $this;
    }
}
