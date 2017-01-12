<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Application\Service\ProjectService;

class UserController extends AbstractController
{

    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        parent::onDispatch($e);
        $this->setLayoutData();
    }

    public function indexAction()
    {
        $id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\CompanyRepository $companyRepository */
        $companyRepository = $entityManager->getRepository('\Application\Entity\Company');
        /** @var \Application\Repository\UserRepository $userRepository */
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        //$all_project = $projectRepository->findAll();
        $user = $userRepository->findOneBy(array('id' => $id));
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
