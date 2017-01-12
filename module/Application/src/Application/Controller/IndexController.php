<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Application\ApplicationTraits\AuthenticationServiceAwareTrait;
use Application\Service\AdminMailer;

class IndexController extends AbstractController
{
    use AuthenticationServiceAwareTrait;

    public function indexAction()
    {
        $viewData = array();
        $form = new \Application\Form\AuthForm('user', array());
        if ($this->getRequest()->isPost()) {
            $viewData['error_msg'] = 'Login or password is incorrect';
            $entityManager = $this->getEntityManager();
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $values = $form->getData();
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
                /** @var \Zend\Authentication\AuthenticationService $authService */
                $authService = $this->getAuthenticationService();
                $authAdapter->setIdentity($values['email']);
                $authAdapter->setCredential($values['password']);
                /** @var \Zend\Authentication\Result $result */
                $result = $authService->authenticate($authAdapter);
                if ($result->isValid()) {
                    $userId = $result->getIdentity();
                    $user = $entityManager->getRepository('Application\Entity\User')->findOneBy(array('id' => $userId, 'status' => 1));
                    if (!$user || $user->getRole()->getCode() != \Application\Entity\Role::ROLE_USER) {
                        $viewData['error_msg'] = 'The user account is disabled';
                        $this->getAuthenticationService()->clearIdentity();
                    } else {
                        $viewData['error_msg'] = null;
                        //$user->setLastLogin(new \DateTime());
                        $entityManager->persist($user);
                        $entityManager->flush();
                        session_regenerate_id(true);
                        // redirect depends on user role
                        $referer = $this->getRequest()->getHeader('Referer')->uri()->getPath();
                        $requestUri = $this->getRequest()->getRequestUri();
                        if ($referer != $requestUri) {
                            return $this->redirect()->toUrl($referer);
                        } else {
                            return $this->redirect()->toUrl('/projects');
                        }
                    }
                }
            }
        }
        $viewData['form'] = $form;
        return new ViewModel($viewData);
    }

    public function logoutAction()
    {
        $this->getAuthenticationService()->clearIdentity();
        return $this->redirect()->toUrl('/');
    }

    public function signupAction()
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\CompanyRepository $companyRepository */
        $companyRepository = $entityManager->getRepository('\Application\Entity\Company');
        /** @var \Application\Repository\RoleRepository $roleRepository */
        $roleRepository = $entityManager->getRepository('\Application\Entity\Role');
        $user = new \Application\Entity\User();

        $form = new \Application\Form\SignupForm('user', array(
                'companies' => $companyRepository->findBy(array(), array('name' => 'asc')),
            )
        );
        $form->setEntityManager($entityManager)
            ->bind($user);
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $user->setRole($roleRepository->findOneBy(array('code' => 'user')));
                $entityManager->persist($user);
//                $values = $form->getData();
//                $company_id = null;
//                if($values['company']){
//                    $company_id = $values['company];
//                } if($values['new_company']){'
//                    $company = new \Application\Entity\Company();
//                    $company->setCreator($user);
//                    $entityManager->persist($company);
//                    $company_id = $company->getId();
//                }
//                $user->setCompanies($companyRepository->findOneById($company_id));

                $entityManager->flush();
                $admin_mailer = new AdminMailer();
                $host = $_SERVER['SERVER_NAME'];
                $massage = "Please go from link to finish registration:  http://{$host}/user/activate/{$user->getId()}";
                $admin_mailer->setSubject("Registration in Applyke Tracker")
                    ->setBody("$massage")->setMailTo($user->getEmail())
                    ->send();
                return $this->redirect()->toUrl('/index/massage/');

            }
        }

        return new ViewModel(array(
            'SignupForm' => $form,
        ));
    }

    public function massageAction()
    {
        return new ViewModel();
    }

}
