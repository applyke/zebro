<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Application\Service\ProjectService;

class ProjectsController extends AbstractController
{

    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        parent::onDispatch($e);
        $this->setLayoutData();
    }

    public function indexAction()
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        //$all_project = $projectRepository->findAll();

        $paginationService = $this->getPaginationService();
        $all_project = $projectRepository->findByWithTotalCount(array(), array('id' => 'DESC'), $this->getPageLimit(), $this->getPageOffset());
        $projectsTotalCount = $projectRepository->getTotalCount();

        return new ViewModel(array(
            'projects' => $all_project,
            'paginator' => $paginationService->createPaginator($projectsTotalCount, $this->getPageNumber(), $this->getPageLimit()),
        ));
    }

    public function createAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        /** @var \Application\Repository\ProjectTypeRepository $projectTypeRepository */
        $projectTypeRepository = $entityManager->getRepository('\Application\Entity\ProjectType');
        /** @var \Application\Repository\ProjectCategoriesRepository $projectCategoriesRepository */
        $projectCategoriesRepository = $entityManager->getRepository('\Application\Entity\ProjectCategories');
        /** @var \Application\Repository\UserRepository $userRepository */
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        $project = new \Application\Entity\Project();

        if (isset($id)) {
            $project = $projectRepository->findOneById($id);
            if (!$project) {
                return $this->notFound();
            }
        }

        $projectForm = new \Application\Form\ProjectForm('project', array(
            'project' => $project,
            'project_types' => $projectTypeRepository->findBy(array(), array('title' => 'asc')),
            'project_categories' => $projectCategoriesRepository->findBy(array(), array('name' => 'asc')),
            'users' => $userRepository->findBy(array(), array('first_name' => 'asc')),
            'backBtnUrl' =>$this->url()->fromRoute('/' ,array(
                'controller' => 'projects',
                'action'=>'index'), array(), true)
        ));

        $projectForm->setEntityManager($entityManager)
            ->bind($project);
        if ($this->getRequest()->isPost()) {
            $projectForm->setData($this->getRequest()->getPost());
            if ($projectForm->isValid()) {
                $values = $projectForm->getData();
                $project->setProjectLead($userRepository->findOneById($values['project_lead']));
                $project->setType($projectTypeRepository->findOneById($values['type']));
                $project->setCategory($projectCategoriesRepository->findOneById($values['category']));
                $entityManager->persist($project);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('/' ,array(
                    'controller' => 'projects',
                    'action'=>'index'), array(), true);
            }
        }

        return new ViewModel(array(
            'projectForm' => $projectForm,
        ));
    }

    public function detailsAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        $project = $projectRepository->findOneById((int)$id);
        if (!$project) {
            return $this->notFound();
        }
        /** @var \Application\Repository\IssueRepository $issueRepository */
        $issueRepository = $entityManager->getRepository('\Application\Entity\Issue');
        $projectsIssues = $issueRepository->findBy(array('project' => $id));

        return new ViewModel(array(
            'project' => $project,
           'projectsIssues' => $projectsIssues,
        ));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        $project = $projectRepository->findOneById((int)$id);
        if (!$project) {
            return $this->notFound();
        }
        return $this->removeEntity($project, array(
            'controller' => 'project'
        ),'/projects');
    }
}
