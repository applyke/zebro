<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\ApplicationTraits\DoctrineEntityManagerAwareTrait;
use Application\ApplicationTraits\PaginationAwareTrait;
use Application\ApplicationTraits\IdentityAwareTrait;
use \Application\ApplicationTraits\DacAwareTrait;

use Zend\View\Resolver;

abstract class AbstractController extends AbstractActionController
{
    use DoctrineEntityManagerAwareTrait;
    use PaginationAwareTrait;
    use IdentityAwareTrait;
    use DacAwareTrait;

    protected $paramsPlugin;
    protected $pageLimit = 10;

    protected function setLayoutData()
    {
    }

    protected function removeEntity($entity = null, $params = array(), $url = 'home')
    {
        if (is_object($entity)) {
            $entityManager = $this->getEntityManager();
            try {
                $entityManager->remove($entity);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Entry removed.');
            } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
                $this->flashMessenger()->addErrorMessage('Entry cant be removed');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage('Delete Error');
            }
        } else {
            $this->flashMessenger()->addInfoMessage('Entry not found');
        }
        return $this->redirect()->toUrl($url);
    }

    protected function checkProjectAccess($user, $permissionCode)
    {
        $dacService = $this->getDacService();
        return $dacService->checkAccess($user, $permissionCode);
    }

    protected function accessDenied()
    {
        $this->getResponse()->setStatusCode(401);
         return  $this->getEvent()->getApplication()->getEventManager()->trigger(\Application\Service\DacService::EVENT_PERMISSION_DENIED, $this->getEvent());
    }

    public function setRenderer(\Zend\View\Renderer\PhpRenderer $phpRenderer)
    {
        $this->renderer = $phpRenderer;
        return $this;
    }

    public function getRenderer()
    {
        return $this->renderer;
    }

    
    protected function notFound()
    {
        $this->getResponse()->setStatusCode(404);
        $this->getEvent()->stopPropagation(true);
        return $this->getEvent()->getApplication()->getEventManager()->trigger(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, $this->getEvent());
    }

    public function getPageNumber($count = null)
    {
        $page = (int)$this->params()->fromRoute('page', 1);
        if (isset($_GET['p'])) {
            $page = (int)$_GET['p'];
        }
        if (!isset($page) || $page < 1) {
            $page = 1;
        }
        if (isset($count)) {
            $maxPage = max(ceil($count / $this->getPageLimit()), 1);
            if ($page > $maxPage) {
                $page = $maxPage;
            }
        }
        return (int)$page;
    }

    public function getPageLimit()
    {
        return $this->pageLimit;
    }

    public function getPageOffset()
    {
        $limit = $this->getPageLimit();
        return $this->getPageNumber() * $limit - $limit;
    }

    public function setPageLimit($limit)
    {
        return $this->pageLimit = $limit;
    }

    public function setParamsPlugin(\Zend\Mvc\Controller\Plugin\Params $paramsPlugin)
    {
        $this->paramsPlugin = $paramsPlugin;
        return $this;
    }
}