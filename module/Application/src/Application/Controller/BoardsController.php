<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

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
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\BoardRepository $boardRepository */
        $boardRepository = $entityManager->getRepository('\Application\Entity\Board');
        /** @var \Application\Repository\BoardsColumnsRepository $boardColumnsRepository */
        $boardColumnsRepository = $entityManager->getRepository('\Application\Entity\BoardsColumns');
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        /** @var \Application\Repository\UserRepository $userRepository */
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        /** @var \Application\Repository\StatusRepository $statusRepository */
        $statusRepository = $entityManager->getRepository('\Application\Entity\Status');
        $board = new \Application\Entity\Board();

        if (isset($id)) {
            $board = $boardRepository->findOneById((int)$id);
            $boardColumns = $boardColumnsRepository->findBy(array('board' => $board->getId()));
            if (!$board) {
                return $this->notFound();
            }
        }

        $boardForm = new \Application\Form\BoardsForm('board', array(
            'board' => $board,
            'projects' => $projectRepository->findBy(array(), array('name' => 'asc')),
            'administrator' => $userRepository->findBy(array(), array('first_name' => 'asc')),
            'status' => $statusRepository->findBy(array(), array('title' => 'asc')),
            'backBtnUrl' => $this->url()->fromRoute('pages/default', array(
                'controller' => 'boards',
                'action' => 'index'), array(), true)
        ));


        $boardForm->setEntityManager($entityManager)
            ->bind($board);
        if ($this->getRequest()->isPost()) {
            $boardForm->setData($this->getRequest()->getPost());
            if ($boardForm->isValid()) {
                $values = $boardForm->getData();

//                $board = $boardRepository->findOneById((int)$id);
//                $boardColumns = $boardColumnsRepository->findBy(array('board'=>$board->getId()));

                $board->setProject($projectRepository->findOneById($values['project']));
                $board->setAdministrator($userRepository->findOneById($values['administrator']));
                $board->setStatus($statusRepository->findOneById($values['status']));

                $entityManager->persist($board);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('pages/default', array(
                    'controller' => 'boards',
                    'action' => 'index'), array(), true);
            }
        }
        return new ViewModel(array(
            'boardForm' => $boardForm,
        ));
    }

    public function detailsAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\BoardRepository $boardRepository */
        $boardRepository = $entityManager->getRepository('\Application\Entity\Board');
        /** @var \Application\Repository\BoardsColumnsRepository $boardColumnsRepository */
        $boardColumnsRepository = $entityManager->getRepository('\Application\Entity\BoardsColumns');
        /** @var \Application\Repository\IssueRepository $issueRepository */
        $issueRepository = $entityManager->getRepository('\Application\Entity\Issue');
        $board = $boardRepository->findOneById((int)$id);
        $boardColumns = $boardColumnsRepository->findBy(array('board' => $board->getId()));
        /** @var \Application\Repository\StatusRepository $statusRepository */
        $statusRepository = $entityManager->getRepository('\Application\Entity\Status');

        if ($this->getRequest()->isPost()) {
            $idColumn = (int)$this->getRequest()->getPost('idColumn');
            $idIssues = (array)explode(',', $this->getRequest()->getPost('idIssue'));
            if ($idColumn) {
                $boardColumn = $boardColumnsRepository->findOneById((int)$idColumn);
                if (!empty($idIssues)) {
                    foreach ($idIssues as $index => $idIssue) {
                        $issue = $issueRepository->findOneById((int)$idIssue);
                        if (!$issue) {
                            return new JsonModel(array('success' => 0));
                        }
                        $issue->setStatus($statusRepository->findOneById((int)$boardColumn->getStatus()->getId()));
                        $issue->setSequenceNumber($index);
                        $entityManager->persist($issue);
                    }
                    $entityManager->flush();
                }
            }
            return new JsonModel(array('success' => 1));
        }

        if (!$board) {
            return $this->notFound();
        }
        $statuses = $statusRepository->findBy(array(), array('title' => 'asc'));
        $issuesArray = array();
        foreach ($statuses as $status) {
            $issuesArray[$status->getId()] = $issueRepository->findBy(array('project' => $board->getProject()->getId(), 'status' => $status), array('sequence_number' => 'asc'));
        }
        return new ViewModel(array(
            'board' => $board,
            'boardColumns' => $boardColumns,
            'issuesArray' => $issuesArray,
        ));
    }
}
