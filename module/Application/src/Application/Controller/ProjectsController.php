<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Service\ProjectService;
use Application\Service\AdminMailer;
use Zend\Dom\Document;

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
        if (false === $this->checkProjectAccess($user, null, 'read_project')) {
            return $this->accessDenied();
        }

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
            'user' => $user,
            'paginator' => $paginationService->createPaginator($projectsTotalCount, $this->getPageNumber(), $this->getPageLimit()),
            'dacService' => $this->getDacService(),
        ));
    }

    public function createAction()
    {
        $user = $this->getIdentityPlugin()->getIdentity();
        if (false === $this->checkProjectAccess($user, null, 'create_project')) {
            return $this->accessDenied();
        }
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
            'backBtnUrl' => $this->url()->fromRoute('pages', array(
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
        $userGlobal = $this->getIdentityPlugin()->getIdentity();
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        /** @var \Application\Repository\ProjectPermissionRepository $projectPermissionRepository */
        $projectPermissionRepository = $entityManager->getRepository('\Application\Entity\ProjectPermission');
        /** @var \Application\Repository\UserRepository $userRepository */
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        $project = $projectRepository->findOneById((int)$id);
        if (!$project) {
            return $this->notFound();
        }
        $projectPermission = null;
        $projectPermissionsForUser = $projectPermissionRepository->findBy(array('project' => $project->getId()));
        if (!$projectPermissionsForUser) {
            return $this->notFound();
        }
        $projectPermissionForm = null;
        if ($this->getRequest()->isPost()) {
            $user = $userRepository->findOneById($this->getRequest()->getPost('user'));
            if (!$user) {
                return $this->notFound();
            }
            $projectPermission = $projectPermissionRepository->findOneBy(array('user' => $user, 'project' => $project));
            $disabledUser = $this->getRequest()->getPost('disabledUser');
            if (isset($disabledUser) && $disabledUser == true) {
                $projectPermission->setDisableUserInProject(1);
                $entityManager->persist($projectPermission);
                $entityManager->flush();
                return new JsonModel(array('success' => true));
            }
            $projectPermissionForm = new \Application\Form\ProjectPermissionForm('projectPermission', array(
                'projectPermission' => $projectPermission,
                'user' => $user,
                'backBtnUrl' => $this->url()->fromRoute('pages/default', array(
                    'controller' => 'projects',
                    'action' => 'users',
                    'id' => $project->getId()), array(), true)
            ));
            $projectPermissionForm->setEntityManager($entityManager)
                ->bind($projectPermission);
            $projectPost = $this->getRequest()->getPost('project');
            if (!$projectPost) {
                $partial = $this->getPartial();
                $data = $partial('application/includes/form/form_horizontal.phtml', array("form" => $projectPermissionForm));
                return new JsonModel(array('html' => $data));
            }

            $projectPermissionForm->setData($this->getRequest()->getPost());
            if ($projectPermissionForm->isValid()) {
                $projectPermission->setUser($user);
                $projectPermission->setProject($project);
                $entityManager->persist($projectPermission);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('pages/default', array(
                    'controller' => 'projects',
                    'action' => 'users',
                    'id' => $project->getId()), array(), true);
            }

        }

        return new ViewModel(array(
            'project' => $project,
            'dacService' => $this->getDacService(),
            'user' => $userGlobal,
            'permissions' => $projectPermissionsForUser,
        ));
    }


    public function inviteAction()
    {

        $project_id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        /** @var \Application\Repository\ProjectPermissionRepository $projectPermissionRepository */
        $projectPermissionRepository = $entityManager->getRepository('\Application\Entity\ProjectPermission');
        /** @var \Application\Repository\UserRepository $userRepository */
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        /** @var \Application\Repository\RoleRepository $roleRepository */
        $roleRepository = $entityManager->getRepository('\Application\Entity\Role');
        $user = $this->getIdentityPlugin()->getIdentity();
        $project = $projectRepository->findOneById((int)$project_id);
        if (!$project) {
            return $this->notFound();
        }

        if (false === $this->checkProjectAccess($user, $project->getId(), 'invite_to_project')) {
            return $this->accessDenied();
        }
        $inviteForm = new \Application\Form\InviteForm();
        if ($this->getRequest()->isPost()) {
            $inviteForm->setData($this->getRequest()->getPost());
            if ($inviteForm->isValid()) {
                $values = $inviteForm->getData();
                $invites_email = array();
                if ($values['email_1']) $invites_email[] = $values['email_1'];
                if ($values['email_2']) $invites_email[] = $values['email_2'];
                if ($values['email_3']) $invites_email[] = $values['email_3'];

                $admin_mailer = new AdminMailer();
                $host = $_SERVER['SERVER_NAME'];
                foreach ($invites_email as $email) {
                    $u = $userRepository->findOneByEmail($email);
                    if ($u) {
                        $company = $project->getCompany();
                        // $u->setCompanies();
                        $entityManager->persist($u);
                        $projectPermission = new  \Application\Entity\ProjectPermission();
                        $projectPermission->setProject($project);
                        $projectPermission->setUser($u);
                        $entityManager->persist($projectPermission);
                        $massage = "User with email {$user->getEmail()} invited you to Project {$project->getName()}.";
                        $admin_mailer->setSubject("Invited you to Project {$project->getName()}")
                            ->setBody("$massage")->setMailTo($u->getEmail())
                            ->send();
                    } else {
                        $new_user = new \Application\Entity\User();
                        $new_user->setEmail($email);
                        $password = substr(md5(microtime()), rand(0, 26), 20);
                        $new_user->setPassword($password);
                        $company = $project->getCompany();
                        // $new_user->setCompanies();
                        $new_user->setRole($roleRepository->findOneBy(array('code' => \Application\Entity\Role::ROLE_USER)));
                        $entityManager->persist($new_user);
                        $projectPermission = new  \Application\Entity\ProjectPermission();
                        $projectPermission->setProject($project);
                        $projectPermission->setUser($new_user);
                        $entityManager->persist($projectPermission);
                        $entityManager->flush();
                        $massage = "User with email {$user->getEmail()} invited you to Project {$project->getName()}. Your  Account is complete. Go from link to reset password http://{$host}/user/reset-password/$password/?email={$email}}";
                        $admin_mailer->setSubject("Registration in Applyke Tracker")
                            ->setBody("$massage")->setMailTo($new_user->getEmail())
                            ->send();
                    }
                }

//                $entityManager->persist($projectPermission);
//                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('pages', array(
                    'controller' => 'projects',
                    'action' => 'users',
                    'id' => $project_id), array(), true);
            }
        }


        return new ViewModel(array(
            'inviteForm' => $inviteForm,
            'project' => $project
        ));
    }

    public function deleteAction()
    {
        $user = $this->getIdentityPlugin()->getIdentity();

        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        $project = $projectRepository->findOneById((int)$id);
        if (!$project) {
            return $this->notFound();
        }
        if (false === $this->checkProjectAccess($user, $project->getId(), 'delete_project')) {
            return $this->accessDenied();
        }
        return $this->removeEntity($project, array(
            'controller' => 'project'
        ), '/projects');
    }
}
