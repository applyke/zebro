<?php

namespace Application\Controller\Setting;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Application\Service\ProjectService;

class StatusController extends AbstractController
{
    public function createAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\GlobalStatusRepository $globalStatusRepository */
        $globalStatusRepository = $entityManager->getRepository('\Application\Entity\GlobalStatus');
        $globalStatus = new \Application\Entity\GlobalStatus();
        if (isset($id)) {
            $globalStatus = $globalStatusRepository->findOneById($id);
            if (!$globalStatus) {
                return $this->notFound();
            }
        }

        $statusForm = new \Application\Form\Setting\StatusForm('globalStatus', array(
            'globalStatus' => $globalStatus,
            'backBtnUrl' => $this->url()->fromRoute('setting', array(
                'controller' => 'setting',
                'action' => 'index'), array(), true)
        ));

        $statusForm->setEntityManager($entityManager)
            ->bind($globalStatus);
        if ($this->getRequest()->isPost()) {
            $statusForm->setData($this->getRequest()->getPost());
            if ($statusForm->isValid()) {
                $values = $statusForm->getData();
                $entityManager->persist($globalStatus);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('setting', array(
                    'controller' => 'setting',
                    'action' => 'index'), array(), true);
            }
        }
        return new ViewModel(array(
            'statusForm' => $statusForm,
        ));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\GlobalStatusRepository $globalStatusRepository */
        $globalStatusRepository = $entityManager->getRepository('\Application\Entity\GlobalStatus');
        $globalStatus = new \Application\Entity\GlobalStatus();
        if (isset($id)) {
            $globalStatus = $globalStatusRepository->findOneById($id);
            if (!$globalStatus) {
                return $this->notFound();
            }
        }
        return $this->removeEntity($globalStatus, array(
            'controller' => 'issues-type'
        ), '/setting');
    }
}