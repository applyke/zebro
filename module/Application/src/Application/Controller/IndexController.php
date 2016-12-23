<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
        $all_project = $projectRepository->findAll();

        return new ViewModel(array(
            'projects' => $all_project,
        ));
    }
}
