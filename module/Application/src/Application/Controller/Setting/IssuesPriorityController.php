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
            'globalIssuePriority' => $globalIssuePriority,
            'backBtnUrl' => $this->url()->fromRoute('setting', array(
                'controller' => 'setting',
                'action' => 'index'), array(), true)
        ));

        $issuesPriorityForm->setEntityManager($entityManager)
            ->bind($globalIssuePriority);
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost()->toArray();
            $file = $this->params()->fromFiles('icon');
            $data = $post;
            $old_icon = $globalIssuePriority->getIcon();
            if ($file) {
                $data = array_merge($post, array('icon' => $file));
            }
            $issuesPriorityForm->setData($data);
            if ($issuesPriorityForm->isValid()) {
                $values = $issuesPriorityForm->getData();
                if ($file) {
                    $string_data = (new \DateTime())->format('Y-m-d-H-i-s');
                    $new_name = '/img/' . $string_data . '-' . $file['name'];
                    $image_saved = move_uploaded_file($file['tmp_name'], dirname(__DIR__) . '/../../../../../public' . $new_name);
                    if ($image_saved && $old_icon) {
                        $this->deleteImage(dirname(__DIR__) . '/../../../../../public' . $old_icon);
                    }
                    $globalIssuePriority->setIcon($new_name);
                }
                $entityManager->persist($globalIssuePriority);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('setting', array(
                    'controller' => 'setting',
                    'action' => 'index'), array(), true);
            }
        }
        return new ViewModel(array(
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
        $this->deleteImage(dirname(__DIR__) . '/../../../../../public' . $globalIssuePriority->getIcon());
        return $this->removeEntity($globalIssuePriority, array(
            'controller' => 'issues-priority'
        ), '/setting');
    }

    /**
     * @param $src_to_image
     * @return bool
     */
    private function deleteImage($src_to_image)
    {
        return unlink($src_to_image);
    }

}