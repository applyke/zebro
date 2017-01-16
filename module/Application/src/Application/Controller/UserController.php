<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Application\Service\ProjectService;
use Application\ApplicationTraits\AuthenticationServiceAwareTrait;


class UserController extends AbstractController
{
    use AuthenticationServiceAwareTrait;

    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        parent::onDispatch($e);
        $this->setLayoutData();
    }

    public function indexAction()
    {
        $user = $this->getIdentityPlugin()->getIdentity();
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\CompanyRepository $companyRepository */
        $companyRepository = $entityManager->getRepository('\Application\Entity\Company');
        /** @var \Application\Repository\UserRepository $userRepository */
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        //$all_project = $projectRepository->findAll();
        //$user = $userRepository->findOneBy(array('id' => $id));
        //TODO: Complete show information about users's companies and projects
        $paginationService = $this->getPaginationService();
        $all_company = $companyRepository->findByWithTotalCount(array('creator' => $user), array('id' => 'DESC'), $this->getPageLimit(), $this->getPageOffset());
        $projectsTotalCount = $companyRepository->getTotalCount();

        return new ViewModel(array(
            'companies' => $all_company,
            'paginator' => $paginationService->createPaginator($projectsTotalCount, $this->getPageNumber(), $this->getPageLimit()),
        ));
    }

    public function activateAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\UserRepository $userRepository */
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        $user = $userRepository->findOneById($id);
        if (!$user && $user->getStatus() != 0) {
            return $this->notFound();
        }
        $user->setStatus(1);
        $entityManager->persist($user);
        $entityManager->flush();
        return new ViewModel();
    }

    public function resetpasswordAction()
    {
        $password = $this->params()->fromRoute('password');
        $email = $this->params()->fromQuery('email',null);
        $entityManager = $this->getEntityManager();

        $authAdapter = new \Application\Service\Auth\Adapter\ObjectRepository(
            array(
                'object_manager' => $entityManager,
                'identity_class' => 'Application\Entity\User',
                'identity_property' => 'email',
                'credential_property' => 'password',
                'credential_callable' => function (\Application\Entity\User $user, $password) {
                    return $password;
                }
            )
        );

        $resetUsersPasswordForm = new \Application\Form\ResetUsersPasswordForm();

        /** @var \Zend\Authentication\AuthenticationService $authService */
        $authService = $this->getAuthenticationService();
        $authAdapter->setIdentity($email);
        $authAdapter->setCredential($password);
        /** @var \Zend\Authentication\Result $result */
        $result = $authService->authenticate($authAdapter);
        $user = null;
        if ($result->isValid()) {
            $userId = $result->getIdentity();
            $user = $entityManager->getRepository('Application\Entity\User')->findOneBy(array('id' => $userId, 'status' => 0));

        } else {
            return $this->redirect()->toUrl('/');
        }
        if ($this->getRequest()->isPost()) {
            $resetUsersPasswordForm->setData($this->getRequest()->getPost());
            if ($resetUsersPasswordForm->isValid()) {
                $values = $resetUsersPasswordForm->getData();
                $user->setPassword($values['password']);
                $user->setStatus(1);
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirect()->toUrl('/user');
            }
        }
        return new ViewModel(array(
            'resetUsersPasswordForm' => $resetUsersPasswordForm,
        ));

    }

//    public function detailsAction()
//    {
//        $id = $this->params()->fromRoute('id');
//        $entityManager = $this->getEntityManager();
//        /** @var \Application\Repository\ProjectRepository $projectRepository */
//        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
//        $project = $projectRepository->findOneById((int)$id);
//        if (!$project) {
//            return $this->notFound();
//        }
//        /** @var \Application\Repository\IssueRepository $issueRepository */
//        $issueRepository = $entityManager->getRepository('\Application\Entity\Issue');
//        $projectsIssues = $issueRepository->findBy(array('project' => $id));
//
//        return new ViewModel(array(
//            'project' => $project,
//            'projectsIssues' => $projectsIssues,
//        ));
//    }
//
//    public function deleteAction()
//    {
//        $id = $this->params()->fromRoute('id');
//        $entityManager = $this->getEntityManager();
//        /** @var \Application\Repository\ProjectRepository $projectRepository */
//        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
//        $project = $projectRepository->findOneById((int)$id);
//        if (!$project) {
//            return $this->notFound();
//        }
//        return $this->removeEntity($project, array(
//            'controller' => 'project'
//        ),'/projects');
//    }
}
