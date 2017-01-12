<?php

namespace Application\Controller\Setting;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Application\Service\ProjectService;

class ProjectTypeController extends AbstractController
{
    public function createAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectTypeRepository $projectTypeRepository */
        $projectTypeRepository = $entityManager->getRepository('\Application\Entity\ProjectType');
        $projectType = new \Application\Entity\GlobalIssueType();
        if (isset($id)) {
            $projectType = $projectTypeRepository->findOneById($id);
            if (!$projectType) {
                return $this->notFound();
            }
        }

        $projectTypeForm = new \Application\Form\Setting\ProjectTypeForm('projectType', array(
            'projectType' => $projectType,
            'backBtnUrl' => $this->url()->fromRoute('setting', array(
                'controller' => 'setting',
                'action' => 'index'), array(), true)
        ));

        $projectTypeForm->setEntityManager($entityManager)
            ->bind($projectType);
        if ($this->getRequest()->isPost()) {
            $projectTypeForm->setData($this->getRequest()->getPost());
            if ($projectTypeForm->isValid()) {
                $values = $projectTypeForm->getData();
                $entityManager->persist($projectType);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('setting', array(
                    'controller' => 'setting',
                    'action' => 'index'), array(), true);
            }
        }
        return new ViewModel(array(
            'projectTypeForm' => $projectTypeForm,
        ));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectTypeRepository $projectTypeRepository */
        $projectTypeRepository = $entityManager->getRepository('\Application\Entity\ProjectType');
        $projectType = new \Application\Entity\GlobalIssueType();
        if (isset($id)) {
            $projectType = $projectTypeRepository->findOneById($id);
            if (!$projectType) {
                return $this->notFound();
            }
        }
        return $this->removeEntity($projectType, array(
            'controller' => 'issues-type'
        ), '/setting');
    }
}