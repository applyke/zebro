<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class IssuesController extends AbstractController
{
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        parent::onDispatch($e);
        $this->setLayoutData();
    }

    public function indexAction()
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\IssueRepository $issueRepository */
        $issueRepository = $entityManager->getRepository('\Application\Entity\Issue');
        $all_issues = $issueRepository->findAll();

        return new ViewModel(array(
            'issues' => $all_issues,
        ));
    }

    public function createAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\IssueRepository $issueRepository */
        $issueRepository = $entityManager->getRepository('\Application\Entity\Issue');
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        /** @var \Application\Repository\IssueTypeRepository $issueTypeRepository */
        $issueTypeRepository = $entityManager->getRepository('\Application\Entity\IssueType');
        /** @var \Application\Repository\IssuePriorityRepository $issuePriorityRepository */
        $issuePriorityRepository = $entityManager->getRepository('\Application\Entity\IssuePriority');
        /** @var \Application\Repository\UserRepository $userRepository */
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        /** @var \Application\Repository\StatusRepository $statusRepository */
        $statusRepository = $entityManager->getRepository('\Application\Entity\Status');
        $issue = new \Application\Entity\Issue();

        if (isset($id)) {
            $issue = $issueRepository->findOneById($id);
            if (!$issue) {
                return $this->notFound();
            }
        }

        $issueForm = new \Application\Form\IssueForm('issue', array(
            'issue' => $issue,
            'projects' => $projectRepository->findBy(array(), array('name' => 'asc')),
            'type' => $issueTypeRepository->findBy(array(), array('title' => 'asc')),
            'priority' => $issuePriorityRepository->findBy(array(), array('title' => 'asc')),
            'assignee' => $userRepository->findBy(array(), array('first_name' => 'asc')),
            'status' => $statusRepository->findBy(array(), array('title' => 'asc')),
            'backBtnUrl' =>$this->url()->fromRoute('home' ,array(
                'controller' => 'issues',
                'action'=>'index'), array(), true)
        ));


        $issueForm->setEntityManager($entityManager)
            ->bind($issue);
        if ($this->getRequest()->isPost()) {
            $issueForm->setData($this->getRequest()->getPost());
            if ($issueForm->isValid()) {
                $values = $issueForm->getData();
//
                $issue->setProject($projectRepository->findOneById($values['project']));
                $issue->setType($issueTypeRepository->findOneById($values['type']));
                $issue->setPriority($issuePriorityRepository->findOneById($values['priority']));
                $issue->setAssignee($userRepository->findOneById($values['assignee']));
                $issue->setReporter($userRepository->findOneById($values['reporter']));
                $issue->setStatus($statusRepository->findOneById($values['status']));

                $entityManager->persist($issue);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('home' ,array(
                    'controller' => 'issues',
                    'action'=>'index'), array(), true);
            }
        }
        return new ViewModel(array(
            'issueForm' => $issueForm,
        ));
    }
}
