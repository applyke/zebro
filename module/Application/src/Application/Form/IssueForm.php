<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Application\Service\HydratorStrategyService;

class IssueForm extends ApplicationFormAbstract
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new HydratorStrategyService);
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');
        $this->setAttribute('class', 'mdl-cell mdl-cell--6-col');
        $projects = array();
        foreach ($options['projects'] as $t) {
            $projects[$t->getId()] = $t->getName();
        }

        $issue_type = array();
        foreach ($options['type'] as $t) {
            $issue_type[$t->getId()] = $t->getTitle();
        }

        $issue_priority = array();
        foreach ($options['priority'] as $t) {
            $issue_priority[$t->getId()] = $t->getTitle();
        }
        $assignee = array();
        foreach ($options['assignee'] as $t) {
            $assignee[$t->getId()] = $t->getFirstName();
        }
        $reporter = array();
        foreach ($options['reporter'] as $t) {
            $reporter[$t->getId()] = $t->getFirstName();
        }
        $issue_status = array();
        foreach ($options['status'] as $t) {
            $issue_status[$t->getId()] = $t->getTitle();
        }
        $issue = null;
        $project = null;
        $issueType = null;
        $issuePriority = null;
        $issueAssignee = null;
        $status = null;
        if (isset($options['issue'])) {
            /** @var \Application\Entity\Issue $issue */
            $issue = $options['issue'];
            /** @var \Application\Entity\Project $project */
            $project = $issue->getProject();
            /** @var \Application\Entity\IssueType $issueType */
            $issueType = $issue->getType();
            /** @var \Application\Entity\IssuePriority $issuePriority */
            $issuePriority = $issue->getPriority();
            /** @var \Application\Entity\User $issueAssignee */
            $issueAssignee = $issue->getAssignee();
            /** @var \Application\Entity\User $issueReporter */
            $status = $issue->getStatus();
        }

        $this->add(new Form\Element\Text('summary', array(
            'label' => "Summary"
        )));
        if (is_object($issue)) {
            $this->get('summary')->setValue($issue->getSummary());
        }

        $this->add(new Form\Element\Text('labels', array(
            'label' => "Labels"
        )));
        if (is_object($issue)) {
            $this->get('labels')->setValue($issue->getLabels());
        }

        $this->add(new Form\Element\Text('description', array(
            'label' => "Description"
        )));
        if (is_object($issue)) {
            $this->get('description')->setValue($issue->getDescription());
        }


        $this->add(new Form\Element\Select('project', array(
            'label' => "Project Name",
            'value_options' => $projects,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));
        if (is_object($project)) {
            $this->get('project')->setValue($project->getId());
        }

        $this->add(new Form\Element\Textarea('description', array(
            'label' => "Description"
        )));
        if (is_object($issue)) {
            $this->get('description')->setValue($issue->getDescription());
        }

        $this->add(new Form\Element\Select('type', array(
            'label' => "Issue Type",
            'value_options' => $issue_type,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));

        if (is_object($issueType)) {
            $this->get('type')->setValue($issueType->getId());
        }

        $this->add(new Form\Element\Select('priority', array(
            'label' => "Priority",
            'value_options' => $issue_priority,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));
        if (is_object($issuePriority)) {
            $this->get('priority')->setValue($issuePriority->getId());
        }

        $this->add(new Form\Element\Select('assignee', array(
            'label' => "Assignee",
            'value_options' => $assignee,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));
        if (is_object($assignee)) {
            $this->get('assignee')->setValue($assignee->getId());
        }

        $this->add(new Form\Element\Select('status', array(
            'label' => "Issue status",
            'value_options' => $issue_status,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));
        if (is_object($status)) {
            $this->get('status')->setValue($status->getId());
        }

        $this->add(array(
            'name' => 'send',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'class' => 'mdl-button mdl-js-button mdl-button--raised mdl-button--colored'
            ),
        ));

        $inputFilter = $this->inputFilter($options);
        $this->setInputFilter($inputFilter);
    }

    protected function inputFilter($options)
    {
        $factory = new FilterFactory();
        $issue = null;
        $project = null;
        $issueType = null;
        $issuePriority = null;
        $assignee = null;
        $status = null;

        if (isset($options['issue'])) {
            /** @var \Application\Entity\Issue $issue */
            $issue = $options['issue'];
            /** @var \Application\Entity\Project $project */
            $project = $issue->getProject();
            /** @var \Application\Entity\IssueType $issueType */
            $issueType = $issue->getType();
            /** @var \Application\Entity\IssuePriority $issuePriority */
            $issuePriority = $issue->getPriority();
            /** @var \Application\Entity\User $assignee */
            $assignee = $issue->getAssignee();
            /** @var \Application\Entity\Status $status */
            $status = $issue->getStatus();
        }

        return $factory->createInputFilter(array(
            'summary' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array(
                    new \Zend\Validator\StringLength(array(
                        'min' => 2,
                        'max' => 128
                    )),
                )
            ),
            'labels' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array(
                    new \Zend\Validator\StringLength(array(
                        'min' => 2,
                        'max' => 128
                    )),
                )
            ),
            'description' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array(
                    new \Zend\Validator\StringLength(array(
                        'min' => 2,
                        'max' => 1024
                    )),
                )
            ),
            'project' => array(
                'required' => false,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\Callback::INVALID_VALUE => 'Project doesn\'t exists',
                            ),
                            'callback' => function ($value, $context = array()) use ($project) {
                                $em = $this->getEntityManager();
                                $projectRepository = $em->getRepository('\Application\Entity\Project');
                                if (is_object($projectRepository->findOneById($value))) {
                                    return true;
                                }
                                return false;
                            },
                        ),
                    ),
                ),
            ),
            'type' => array(
                'required' => false,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\Callback::INVALID_VALUE => 'Type doesn\'t exists',
                            ),
                            'callback' => function ($value, $context = array()) use ($issueType) {
                                $em = $this->getEntityManager();
                                $issueTypeRepository = $em->getRepository('\Application\Entity\IssueType');
                                if (is_object($issueTypeRepository->findOneById($value))) {
                                    return true;
                                }
                                return false;
                            },
                        ),
                    ),
                ),
            ),
            'priority' => array(
                'required' => false,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\Callback::INVALID_VALUE => 'Priority doesn\'t exists',
                            ),
                            'callback' => function ($value, $context = array()) use ($issuePriority) {
                                $em = $this->getEntityManager();
                                $issuePriorityRepository = $em->getRepository('\Application\Entity\IssuePriority');
                                if (is_object($issuePriorityRepository->findOneById($value))) {
                                    return true;
                                }
                                return false;
                            },
                        ),
                    ),
                ),
            ),
            'assignee' => array(
                'required' => false,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\Callback::INVALID_VALUE => 'User doesn\'t exists',
                            ),
                            'callback' => function ($value, $context = array()) use ($assignee) {
                                $em = $this->getEntityManager();
                                $userRepository = $em->getRepository('\Application\Entity\User');
                                if (is_object($userRepository->findOneById($value))) {
                                    return true;
                                }
                                return false;
                            },
                        ),
                    ),
                ),
            ),
            'status' => array(
                'required' => false,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\Callback::INVALID_VALUE => 'Status doesn\'t exists',
                            ),
                            'callback' => function ($value, $context = array()) use ($status) {
                                $em = $this->getEntityManager();
                                $statusRepository = $em->getRepository('\Application\Entity\Status');
                                if (is_object($statusRepository->findOneById($value))) {
                                    return true;
                                }
                                return false;
                            },
                        ),
                    ),
                ),
            ),
        ));
    }
}
