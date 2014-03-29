<?php

namespace Search\Service;

use Search\IndexProvider\IndexProviderInterface;
use Search\Option\IndexOptions;

class IndexService
{
    protected $indexProviders = array();
    protected $defaultTypes = array('default'=>array());

    public function index(array $types=null)
    {
        $types = $types ?: $this->getDefaultTypes();

        $return = array();
        foreach ($types as $name => $options) {
            $return[$name] = $this->indexOne($name, $options);
        }

        return $return;
    }

    public function indexOne($name, $options=null)
    {
        $options = $this->getOptions($options);
        $results = $this->getIndexProvider($name)->index($options);
        return array(
            'options' => $options,
            'results' => $results,
        );
    }

    public function getOptions($options=null)
    {
        if (is_array($options)) {
            $options = new IndexOptions($options);
        } elseif ($options === null) {
            $options = new IndexOptions(array());
        } elseif (!$options instanceOf IndexOptions) {
            throw new \Exception('index options must be array, instance of index options, or null');
        }
        return $options;
    }

    public function getIndexProvider($name)
    {
        if (!is_string($name)) {
            throw new \Exception('index provider name must be string, got'. gettype($name));
        }
        if (!array_key_exists($name, $this->indexProviders)) {
            throw new \Exception('index provider not registered - ' . $name);
        }
        $provider = $this->indexProviders[$name];
        if (!$provider instanceOf IndexProviderInterface) {
            throw new \Exception('index provider must be instance of IndexProviderInterface');
        }
        return $provider;
    }

    public function getIndexProviders()
    {
        return $this->indexProviders;
    }

    public function addIndexProvider($name, IndexProviderInterface $provider)
    {
        $this->indexProviders[$name] = $provider;
        return $this;
    }

    public function setIndexProviders($indexProviders)
    {
        $this->indexProviders = array();
        foreach ($indexProviders as $name => $provider) {
            $this->addIndexProvider($name, $provider);
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
