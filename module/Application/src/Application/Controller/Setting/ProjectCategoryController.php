<?php

namespace Application\Controller\Setting;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Application\Service\ProjectService;

class ProjectCategoryController extends AbstractController
{
    public function createAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectCategoriesRepository $projectCategoriesRepository */
        $projectCategoriesRepository = $entityManager->getRepository('\Application\Entity\ProjectCategories');
        $projectCategories = new \Application\Entity\ProjectCategories();
        if (isset($id)) {
            $projectCategories = $projectCategoriesRepository->findOneById($id);
            if (!$projectCategories) {
                return $this->notFound();
            }
        }

        $projectCategoryForm = new \Application\Form\Setting\ProjectCategoryForm('projectCategories', array(
            'projectCategories' => $projectCategories,
            'backBtnUrl' => $this->url()->fromRoute('setting', array(
                'controller' => 'setting',
                'action' => 'index'), array(), true)
        ));

        $projectCategoryForm->setEntityManager($entityManager)
            ->bind($projectCategories);
        if ($this->getRequest()->isPost()) {
            $projectCategoryForm->setData($this->getRequest()->getPost());
            if ($projectCategoryForm->isValid()) {
                $values = $projectCategoryForm->getData();
                $entityManager->persist($projectCategories);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('setting', array(
                    'controller' => 'setting',
                    'action' => 'index'), array(), true);
            }
        }
        return new ViewModel(array(
            'projectCategoryForm' => $projectCategoryForm,
        ));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectCategoriesRepository $projectCategoriesRepository */
        $projectCategoriesRepository = $entityManager->getRepository('\Application\Entity\ProjectCategories');
        $projectCategories = new \Application\Entity\ProjectCategories();
        if (isset($id)) {
            $projectCategories = $projectCategoriesRepository->findOneById($id);
            if (!$projectCategories) {
                return $this->notFound();
            }
        }
        return $this->removeEntity($projectCategories, array(
            'controller' => 'project-category'
        ), '/setting');
    }
}