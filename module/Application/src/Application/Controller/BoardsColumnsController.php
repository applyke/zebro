<?php
namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;


class BoardsColumnsController extends AbstractController
{
    public function createAction()
    {
        // /boards/details/
        $id = $this->params()->fromRoute('id');
        $id2 = $this->params()->fromRoute('id2');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\BoardRepository $boardRepository */
        $boardRepository = $entityManager->getRepository('\Application\Entity\Board');
        /** @var \Application\Repository\BoardsColumnsRepository $boardColumnsRepository */
        $boardColumnsRepository = $entityManager->getRepository('\Application\Entity\BoardsColumns');
        /** @var \Application\Repository\StatusRepository $statusRepository */
        $statusRepository = $entityManager->getRepository('\Application\Entity\Status');
        $boardsColumns = new \Application\Entity\BoardsColumns();
        $consecutiveNumber = 0;
        if (isset($id2)) {
            $boardsColumns = $boardColumnsRepository->findOneById((int)$id2);
            if (!$boardsColumns) {
                return $this->notFound();
            }
            $consecutiveNumber = $boardsColumns->getConsecutiveNumber();
        }
        $boards_from_id = null;
        if (isset($id)) {
            $boards_from_id = $boardRepository->findOneById((int)$id);
            $columns = $boardColumnsRepository->findOneBy(array('board' => $boards_from_id->getId()), array('consecutive_number' => 'desc'));
            $consecutiveNumber = $columns->getConsecutiveNumber() + 1;
        }

        $boardsColumnsForm = new \Application\Form\BoardsColumnsForm('boardscolumns', array(
            'boardColumns' => $boardsColumns,
            'board' => $boardRepository->findBy(array(), array('title' => 'asc')),
            'boards_from_id' => $boards_from_id,
            'status' => $statusRepository->findBy(array(), array('title' => 'asc')),
            'backBtnUrl' => $this->url()->fromRoute('pages/default', array(
                'controller' => 'boards',
                'action' => 'index'), array(), true)
        ));

        $boardsColumnsForm->setEntityManager($entityManager)
            ->bind($boardsColumns);
        if ($this->getRequest()->isPost()) {
            $boardsColumnsForm->setData($this->getRequest()->getPost());
            if ($boardsColumnsForm->isValid()) {
                $values = $boardsColumnsForm->getData();
                $boardsColumns->setBoard($boardRepository->findOneById($values['board']));
                $boardsColumns->setStatus($statusRepository->findOneById($values['status']));
                $boardsColumns->setConsecutiveNumber($consecutiveNumber);
                $entityManager->persist($boardsColumns);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toUrl('/boards/details/' . $id);
            }
        }
        return new ViewModel(array(
            'boardsColumnsForm' => $boardsColumnsForm,
        ));
    }
}