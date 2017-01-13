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
        $user = $this->getIdentityPlugin()->getIdentity();
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        //$all_project = $projectRepository->findAll();

        /** @var \Application\Repository\ProjectPermissionRepository $projectPermissionRepository */
        $projectPermissionRepository = $entityManager->getRepository('\Application\Entity\ProjectPermission');

        $paginationService = $this->getPaginationService();
        $all_project = $projectPermissionRepository->getUsersProjectWithPagination($user, array('id' => 'DESC'), $this->getPageLimit(), $this->getPageOffset());
        $projectsTotalCount = $projectPermissionRepository->getTotalCount();

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
        /** @var \Application\Repository\ProjectPermissionRepository $projectPermissionRepository */
        $projectPermissionRepository = $entityManager->getRepository('\Application\Entity\ProjectPermission');
        /** @var \Application\Repository\GlobalStatusRepository $globalStatusRepository */
        $globalStatusRepository = $entityManager->getRepository('\Application\Entity\GlobalStatus');
        /** @var \Application\Repository\GlobalIssueTypeRepository $globalIssueTypeRepository */
        $globalIssueTypeRepository = $entityManager->getRepository('\Application\Entity\GlobalIssueType');
        /** @var \Application\Repository\GlobalIssuePriorityRepository $globalIssuePriorityRepository */
        $globalIssuePriorityRepository = $entityManager->getRepository('\Application\Entity\GlobalIssuePriority');
        /** @var \Application\Repository\StatusRepository $statusRepository */
        $statusRepository = $entityManager->getRepository('\Application\Entity\Status');
        /** @var \Application\Repository\IssueTypeRepository $issueTypeRepository */
        $issueTypeRepository = $entityManager->getRepository('\Application\Entity\IssueType');
        /** @var \Application\Repository\IssuePriorityRepository $issuePriorityRepository */
        $issuePriorityRepository = $entityManager->getRepository('\Application\Entity\IssuePriority');
        $project = null;
        $project_create = false;

        if (isset($id)) {
            $project = $projectRepository->findOneById($id);
            if (!$project) {
                return $this->notFound();
            }
        }
        if (!$project) {
            $project = new \Application\Entity\Project();
            $project_create = true;
        }


        $projectForm = new \Application\Form\ProjectForm('project', array(
            'project' => $project,
            'project_types' => $projectTypeRepository->findBy(array(), array('title' => 'asc')),
            'project_categories' => $projectCategoriesRepository->findBy(array(), array('name' => 'asc')),
            'users' => $userRepository->findBy(array(), array('first_name' => 'asc')),
            'backBtnUrl' => $this->url()->fromRoute('pages/default', array(
                'controller' => 'projects',
                'action' => 'index'), array(), true)
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
                $entityManager->persist($projectPermissionRepository->getLeadPermissionToProject($userRepository->findOneById($values['project_lead']), $project));
                if ($project_create) {
                    $statusRepository->saveStatusesForProject($project, $globalStatusRepository->findAll());
                    $issueTypeRepository->saveIssueTypeForProject($project, $globalIssueTypeRepository->findAll());
                    $issuePriorityRepository->saveIssuePriorityForProject($project, $globalIssuePriorityRepository->findAll());
                }
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('pages/default', array(
                    'controller' => 'projects',
                    'action' => 'index'));
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

    public function usersAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        /** @var \Application\Repository\ProjectPermissionRepository $projectPermissionRepository */
        $projectPermissionRepository = $entityManager->getRepository('\Application\Entity\ProjectPermission');
        $project = $projectRepository->findOneById((int)$id);
        if (!$project) {
            return $this->notFound();
        }
        $users_in_project = $projectPermissionRepository->getUsersInProject($project);
        return new ViewModel(array(
            'project' => $project,
            'users_in_project' => $users_in_project,
        ));
    }

    public function permissionAction()
    {
        $project_id = $this->params()->fromRoute('id');
        $user_id = $this->params()->fromRoute('id2');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        /** @var \Application\Repository\ProjectPermissionRepository $projectPermissionRepository */
        $projectPermissionRepository = $entityManager->getRepository('\Application\Entity\ProjectPermission');
        /** @var \Application\Repository\UserRepository $userRepository */
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        $project = $projectRepository->findOneById((int)$project_id);
        $user = null;
        if ($user_id) {
            $user = $userRepository->findOneById((int)$user_id);
        }
        $projectPermission = null;
        if (!$project) {
            return $this->notFound();
        }
        if ($user) {
            $projectPermission = $projectPermissionRepository->findOneBy(array('user' => $user, 'project' => $project));
        }
        if (!$projectPermission) {
            $projectPermission = new  \Application\Entity\ProjectPermission();
            $projectPermission->setProject($project);
            if ($user) {
                $projectPermission->setUser($user);
            }

        }
        $companies_projects = $projectRepository->getProjectsInCompany($project->getCompany());
        $projectPermissionForm = new \Application\Form\ProjectPermissionForm('projectPermission', array(
            'projectPermission' => $projectPermission,
            'companies_projects' => $companies_projects,
            'companies_users' => $userRepository->getUsersInCompany($project->getCompany()),
            'backBtnUrl' => $this->url()->fromRoute('pages', array(
                'controller' => 'projects',
                'action' => 'users',
                'id' => $project_id), array(), true)
        ));

        $projectPermissionForm->setEntityManager($entityManager)
            ->bind($projectPermission);
        if ($this->getRequest()->isPost()) {
            $projectPermissionForm->setData($this->getRequest()->getPost());
            if ($projectPermissionForm->isValid()) {
                $values = $projectPermissionForm->getData();
                $entityManager->persist($projectPermission);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('pages', array(
                    'controller' => 'projects',
                    'action' => 'users',
                    'id' => $project_id), array(), true);
            }
        }

        return new ViewModel(array(
            'projectPermissionForm' => $projectPermissionForm,
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
        ), '/projects');
    }
}
