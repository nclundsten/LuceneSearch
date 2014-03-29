<?php

return array(
    'service_manager' => array(
        'invokables' => array(
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'search' => 'Search\Controller\SearchController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'router' => array(
        'routes' => array(
            'searchquery' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/search[/:query]',
                    'defaults' => array(
                        'controller' => 'search',
                        'action'     => 'index',
                    ),
                ),
                'prioritiy' => -1000,
            ),
            'search' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/search',
                    'defaults' => array(
                        'controller' => 'search',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'organization-select-modal' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/organization-select[/:query]',
                            'defaults' => array(
                                'controller' => 'search',
                                'action'     => 'organization-select',
                            ),
                        ),
                    ),
                    'search-pane' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/search-pane/:name',
                            'defaults' => array(
                                'controller' => 'search',
                                'action'     => 'search-pane',
                            ),
                        ),
                    ),
                    'build-index' => array(
                        //todo: turn into console route and remove controller action
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/build-index',
                            'defaults' => array(
                                'controller' => 'search',
                                'action'     => 'build-index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
