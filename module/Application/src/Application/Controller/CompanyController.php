<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Application\Service\ProjectService;

class CompanyController extends AbstractController
{

    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        parent::onDispatch($e);
        $this->setLayoutData();
    }

    public function indexAction()
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\CompanyRepository $companyRepository */
        $companyRepository = $entityManager->getRepository('\Application\Entity\Company');
        //$all_project = $projectRepository->findAll();

        $paginationService = $this->getPaginationService();
        $all_company = $companyRepository->findByWithTotalCount(array(), array('id' => 'DESC'), $this->getPageLimit(), $this->getPageOffset());
        $projectsTotalCount = $companyRepository->getTotalCount();

        return new ViewModel(array(
            'companies' => $all_company,
            'paginator' => $paginationService->createPaginator($projectsTotalCount, $this->getPageNumber(), $this->getPageLimit()),
        ));
    }

    public function createAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\CompanyRepository $companyRepository */
        $companyRepository = $entityManager->getRepository('\Application\Entity\Company');
        /** @var \Application\Repository\UserRepository $userRepository */
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        $company = new \Application\Entity\Company();

        if (isset($id)) {
            $company = $companyRepository->findOneById($id);
            if (!$company) {
                return $this->notFound();
            }
        }

        $companyForm = new \Application\Form\CompanyForm('company', array(
            'company' => $company,
            'backBtnUrl' =>$this->url()->fromRoute('pages', array(
                'controller' => 'company',
                'action'=>'index'), array(), true)
        ));

        $companyForm->setEntityManager($entityManager)
            ->bind($company);
        if ($this->getRequest()->isPost()) {
            $companyForm->setData($this->getRequest()->getPost());
            if ($companyForm->isValid()) {
                $values = $companyForm->getData();
                $identity =  $this->zfcUserAuthentication()->getIdentity();
                $company->setCreator($userRepository->findOneById($identity->getId()));
                $entityManager->persist($company);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('pages', array(
                    'controller' => 'projects',
                    'action' => 'index'), array(), true);
            }
        }

        return new ViewModel(array(
            'projectForm' => $companyForm,
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
