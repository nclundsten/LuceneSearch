<?php

namespace Search;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Paginator\Paginator;
use Search\Service;
use Search\SearchProvider;
use Search\IndexProvider;
use Search\Mapper;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Search\Service\SearchService' => function ($sm) {
                    $service = new Service\SearchService();
                    $lucene = $sm->get('Search\SearchProvider\DefaultLuceneProvider');
                    $service->addSearchProvider('default', $lucene);
                    return $service;
                },
                'Search\Service\IndexService' => function ($sm) {
                    $service = new Service\IndexService();
                    $lucene = $sm->get('Search\IndexProvider\DefaultLuceneProvider');
                    $service->addIndexProvider('default', $lucene);
                    return $service;
                },
                'Search\SearchProvider\DefaultLuceneProvider' => function ($sm) {
                    $hitMapper = new Mapper\ExampleMapper();
                    return new SearchProvider\DefaultLuceneProvider('/dev/shm', $hitMapper);
                },
                'Search\IndexProvider\DefaultLuceneProvider' => function ($sm) {
                    $indexMapper = new Mapper\ExampleMapper();
                    return new IndexProvider\DefaultLuceneProvider('/dev/shm', $indexMapper);
                },
            ),
        );
    }
}
