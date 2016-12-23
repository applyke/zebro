<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class BoardsController extends AbstractController
{
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        parent::onDispatch($e);
        $this->setLayoutData();
    }

    public function indexAction()
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\BoardRepository $boardRepository */
        $boardRepository = $entityManager->getRepository('\Application\Entity\Board');
        $all_boards = $boardRepository->findAll();

        return new ViewModel(array(
            'boards' => $all_boards,
        ));
    }
    public function createAction()
    {
//        $id = $this->params()->fromRoute('id');
//        $entityManager = $this->getEntityManager();
//        /** @var \Application\Repository\IssueRepository $issueRepository */
//        $issueRepository = $entityManager->getRepository('\Application\Entity\Issue');
//        /** @var \Application\Repository\ProjectRepository $projectRepository */
//        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
//        /** @var \Application\Repository\IssueTypeRepository $issueTypeRepository */
//        $issueTypeRepository = $entityManager->getRepository('\Application\Entity\IssueType');
//        /** @var \Application\Repository\IssuePriorityRepository $issuePriorityRepository */
//        $issuePriorityRepository = $entityManager->getRepository('\Application\Entity\IssuePriority');
//        /** @var \Application\Repository\UserRepository $userRepository */
//        $userRepository = $entityManager->getRepository('\Application\Entity\User');
//        /** @var \Application\Repository\StatusRepository $statusRepository */
//        $statusRepository = $entityManager->getRepository('\Application\Entity\Status');
//        $issue = new \Application\Entity\Issue();
//
//        if (isset($id)) {
//            $issue = $issueRepository->findOneById($id);
//            if (!$issue) {
//                return $this->notFound();
//            }
//        }
//
//        $issueForm = new \Application\Form\IssueForm('issue', array(
//            'issue' => $issue,
//            'projects' => $projectRepository->findBy(array(), array('name' => 'asc')),
//            'issue_types' => $issueTypeRepository->findBy(array(), array('title' => 'asc')),
//            'issue_priority' => $issuePriorityRepository->findBy(array(), array('title' => 'asc')),
//            'assignee' => $userRepository->findBy(array(), array('first_name' => 'asc')),
//            'reporter' => $userRepository->findBy(array(), array('first_name' => 'asc')),
//            'status' => $statusRepository->findBy(array(), array('title' => 'asc')),
//            'backBtnUrl' =>$this->url()->fromRoute('home' ,array(
//                'controller' => 'issues',
//                'action'=>'index'), array(), true)
//        ));
//
//
//        $issueForm->setEntityManager($entityManager)
//            ->bind($issue);
//        if ($this->getRequest()->isPost()) {
//            $issueForm->setData($this->getRequest()->getPost());
//            if ($issueForm->isValid()) {
//                $values = $issueForm->getData();
////
//                $issue->setProject($projectRepository->findOneById($values['project']));
//                $issue->setType($issueTypeRepository->findOneById($values['issue_type']));
//                $issue->setPriority($issuePriorityRepository->findOneById($values['issue_priority']));
//                $issue->setAssignee($userRepository->findOneById($values['assignee']));
//                $issue->setReporter($userRepository->findOneById($values['reporter']));
//                $issue->setStatus($statusRepository->findOneById($values['status']));
//
//                $entityManager->persist($issue);
//                $entityManager->flush();
//                $this->flashMessenger()->addSuccessMessage('Saved');
//                return $this->redirect()->toUrl('/issues');
//            }
//        }
//        return new ViewModel(array(
//            'issueForm' => $issueForm,
//        ));
    }
    public function detailsAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\BoardRepository $boardRepository */
        $boardRepository = $entityManager->getRepository('\Application\Entity\Board');
        /** @var \Application\Repository\BoardsColumnsRepository $boardColumnsRepository */
        $boardColumnsRepository = $entityManager->getRepository('\Application\Entity\BoardsColumns');
        $board = $boardRepository->findOneById((int)$id);
        $boardColumns = $boardColumnsRepository->findBy(array('board'=>$board->getId()));
        if (!$board) {
            return $this->notFound();
        }
        return new ViewModel(array(
            'board' => $board,
            'boardColumns'=>$boardColumns
        ));
    }
}
