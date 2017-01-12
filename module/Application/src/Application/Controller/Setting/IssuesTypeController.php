<?php

namespace Application\Controller\Setting;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Application\Service\ProjectService;

class IssuesTypeController extends AbstractController
{

    public function createAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\GlobalIssueTypeRepository $globalIssueTypeRepository */
        $globalIssueTypeRepository = $entityManager->getRepository('\Application\Entity\GlobalIssueType');
        $globalIssueType = new \Application\Entity\GlobalIssueType();
        if (isset($id)) {
            $globalIssueType = $globalIssueTypeRepository->findOneById($id);
            if (!$globalIssueType) {
                return $this->notFound();
            }
        }

        $issuesTypeForm = new \Application\Form\Setting\IssuesTypeForm('globalIssuePriority', array(
            'globalIssuePriority' => $globalIssueType,
            'backBtnUrl' => $this->url()->fromRoute('setting', array(
                'controller' => 'setting',
                'action' => 'index'), array(), true)
        ));

        $issuesTypeForm->setEntityManager($entityManager)
            ->bind($globalIssueType);
        if ($this->getRequest()->isPost()) {
            $issuesTypeForm->setData($this->getRequest()->getPost());
            if ($issuesTypeForm->isValid()) {
                $values = $issuesTypeForm->getData();
                $entityManager->persist($globalIssueType);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('setting', array(
                    'controller' => 'setting',
                    'action' => 'index'), array(), true);
            }
        }
        return new ViewModel(array(
            'issuesTypeForm' => $issuesTypeForm,
        ));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\GlobalIssueTypeRepository $globalIssueTypeRepository */
        $globalIssueTypeRepository = $entityManager->getRepository('\Application\Entity\GlobalIssueType');
        $globalIssueType = new \Application\Entity\GlobalIssueType();
        if (isset($id)) {
            $globalIssueType = $globalIssueTypeRepository->findOneById($id);
            if (!$globalIssueType) {
                return $this->notFound();
            }
        }
        return $this->removeEntity($globalIssueType, array(
            'controller' => 'issues-type'
        ), '/setting');
    }
}