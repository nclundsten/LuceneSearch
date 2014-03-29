<?php

namespace Search\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Response;

class SearchController extends AbstractActionController
{
    protected $searchService;

    protected $searchPaneTemplate = 'search/result-pane';

    public function indexAction()
    {
        $post = $this->params()->fromPost();
        $pq   = isset($post['query']) ? $post['query'] : null;
        if ($pq) {
            return $this->redirect()->toRoute('searchquery', array('query' => $pq));
        }
        $query = $this->params('query') ?: '';

        $result = $this->getSearchService()->search($query);
        return new ViewModel(array(
            'query'  => $query,
            'result' => $result,
        ));
    }

    //todo: this is temporary only
    public function buildIndexAction()
    {
        $this->getServiceLocator()->get('Search\Service\IndexService')->index();
        die('done');
    }

    public function searchPaneAction()
    {
        $name = $this->params('name');
        $params = $this->params()->fromQuery();

        $result = $this->getSearchService()->search($params['query'], [$name => $params]);

        $vars = array(
            'results' => $result[$name]['results'],
            'options' => $result[$name]['options'],
            'name'    => $name,
        );

        $view = new ViewModel($vars);
        $view->setTemplate($this->getSearchPaneTemplate())->setTerminal(true);

        return $view;
    }

    public function getSearchPaneTemplate()
    {
        return $this->searchPaneTemplate;
    }

    public function setSearchPaneTemplate($searchPaneTemplate)
    {
        $this->searchPaneTemplate = $searchPaneTemplate;
        return $this;
    }

    public function getSearchService()
    {
        if (null === $this->searchService) {
            $this->searchService = $this->getServiceLocator()
                ->get('Search\Service\SearchService');
        }
        return $this->searchService;
    }

    public function setSearchService($searchService)
    {
        $this->searchService = $searchService;
        return $this;
    }
}
