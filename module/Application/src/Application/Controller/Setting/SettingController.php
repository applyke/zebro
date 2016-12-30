<?php

namespace Application\Controller\Setting;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class SettingController extends AbstractController
{

    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        parent::onDispatch($e);
        $this->setLayoutData();
    }

    public function indexAction()
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\GlobalStatusRepository $globalStatusRepository */
        $globalStatusRepository = $entityManager->getRepository('\Application\Entity\GlobalStatus');
        /** @var \Application\Repository\GlobalIssuePriorityRepository $globalIssuePriorityRepository  */
        $globalIssuePriorityRepository = $entityManager->getRepository('\Application\Entity\GlobalIssuePriority');
        /** @var \Application\Repository\GlobalIssueTypeRepository $globalIssueTypeRepository  */
        $globalIssueTypeRepository = $entityManager->getRepository('\Application\Entity\GlobalIssueType');
        /** @var \Application\Repository\ProjectCategoriesRepository $projectCategoriesRepository  */
        $projectCategoriesRepository = $entityManager->getRepository('\Application\Entity\ProjectCategories');
        /** @var \Application\Repository\ProjectTypeRepository $projectTypeRepository   */
        $projectTypeRepository = $entityManager->getRepository('\Application\Entity\ProjectType');

        $statuses = $globalStatusRepository->findAll();
        $issuePriorities = $globalIssuePriorityRepository->findAll();
        $issueTypes = $globalIssueTypeRepository->findAll();
        $projectCategories = $projectCategoriesRepository->findAll();
        $projectTypes = $projectTypeRepository->findAll();

        return new ViewModel(array(
            'statuses' => $statuses,
            'issuePriorities'=> $issuePriorities,
            'issueTypes'=>$issueTypes,
            'projectCategories' => $projectCategories,
            'projectTypes' => $projectTypes,
        ));
    }

}