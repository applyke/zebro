<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
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
        $user = $this->getIdentityPlugin()->getIdentity();
      
        $companyId = null;
        if ($this->getRequest()->isPost()) {
            $company = $companyRepository->findOneById($this->getRequest()->getPost('idCompany'));
            if($company){
                $companyId = $company->getId();
            }
            $user->setCompanyAccount($companyId);
            $entityManager->persist($user);
            $entityManager->flush();
            return new JsonModel(array('success' => true));
        }
        $usersCompanies = $companyRepository->findBy(array('creator' => $user));
        $user_work_in_company = $user->getCompanies()->toArray();
        return new ViewModel(array(
            'user' => $user,
            'usersCompanies' => $usersCompanies,
            'user_work_in_company' => $user_work_in_company,
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
            'backBtnUrl' => $this->url()->fromRoute('pages', array(
                'controller' => 'company',
                'action' => 'index'), array(), true)
        ));
        $companyForm->setEntityManager($entityManager)
            ->bind($company);
        if ($this->getRequest()->isPost()) {
            $companyForm->setData($this->getRequest()->getPost());
            if ($companyForm->isValid()) {
                $values = $companyForm->getData();
                $user = $this->getIdentityPlugin()->getIdentity();
                $company->setCreator($userRepository->findOneById($user->getId()));
                $entityManager->persist($company);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('pages', array(
                    'controller' => 'company',
                    'action' => 'index'), array(), true);
            }
        }
        return new ViewModel(array(
            'companyForm' => $companyForm,
        ));
    }

    public function detailsAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\CompanyRepository $companyRepository */
        $companyRepository = $entityManager->getRepository('\Application\Entity\Company');
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        /** @var \Application\Repository\ProjectPermissionRepository $projectPermissionRepository */
        $projectPermissionRepository = $entityManager->getRepository('\Application\Entity\ProjectPermission');
        /** @var \Application\Repository\UserRepository $userRepository */
        $userRepository = $entityManager->getRepository('\Application\Entity\User');

        $user = $this->getIdentityPlugin()->getIdentity();
        $company = $companyRepository->findOneById((int)$id);
        if (!$company) {
            return $this->notFound();
        }

        $companiesProjects = $projectRepository->findBy(array('company' => $company));
//        $companiesUsers = $userRepository->findBy(array('companies' => $company) );
        // $projectPermission = $projectPermissionRepository->findBy(array('user'=>$user, 'company'=>$company));

//        if (!$projectPermission) {
//            return $this->notFound(); //TODO: change to page where write about don't have permission
//        }

        return new ViewModel(array(
            'company' => $company,
            'projects' => $companiesProjects,
            //'arrayUsers' => $companiesUsers,
            //'projectPermission' => $projectPermission
        ));
    }


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
