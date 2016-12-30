<?php

namespace Application\Controller\Setting;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Application\Service\ProjectService;

class IssuesPriorityController extends AbstractController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function createAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\GlobalIssuePriorityRepository $globalIssuePriorityRepository */
        $globalIssuePriorityRepository = $entityManager->getRepository('\Application\Entity\GlobalIssuePriority');
        $globalIssuePriority = new \Application\Entity\GlobalIssuePriority();
        if (isset($id)) {
            $globalIssuePriority = $globalIssuePriorityRepository->findOneById($id);
            if (!$globalIssuePriority) {
                return $this->notFound();
            }
        }

        $issuesPriorityForm = new \Application\Form\Setting\IssuesPriorityForm('globalIssuePriority', array(
            'globalIssuePriority' =>  $globalIssuePriority,
            'backBtnUrl' =>$this->url()->fromRoute('setting' ,array(
                'controller' => 'setting',
                'action'=>'index'), array(), true)
        ));

        $issuesPriorityForm->setEntityManager($entityManager)
            ->bind($globalIssuePriority);
        if ($this->getRequest()->isPost()) {
            $issuesPriorityForm->setData($this->getRequest()->getPost());
            if ($issuesPriorityForm->isValid()) {
                $values = $issuesPriorityForm->getData();
                $entityManager->persist($globalIssuePriority);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('setting' ,array(
                    'controller' => 'setting',
                    'action'=>'index'), array(), true);
            }
        }
        return new ViewModel( array(
            'issuesPriorityForm' => $issuesPriorityForm,
        ));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\GlobalIssuePriorityRepository $globalIssuePriorityRepository */
        $globalIssuePriorityRepository = $entityManager->getRepository('\Application\Entity\GlobalIssuePriority');
        $globalIssuePriority = new \Application\Entity\GlobalIssuePriority();
        if (isset($id)) {
            $globalIssuePriority = $globalIssuePriorityRepository->findOneById($id);
            if (!$globalIssuePriority) {
                return $this->notFound();
            }
        }
        return $this->removeEntity($globalIssuePriority, array(
            'controller' => 'issues-priority'
        ),'/setting');
    }
}