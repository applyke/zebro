<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Application\Service\HydratorStrategyService;

class BoardsForm extends ApplicationFormAbstract
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

        $administrators = array();
        foreach ($options['administrator'] as $t) {
            $administrators[$t->getId()] = $t->getFirstName();
        }
        $boards_status = array();
        foreach ($options['status'] as $t) {
            $boards_status[$t->getId()] = $t->getTitle();
        }
        $board = null;
        $project = null;
        $administrator = null;
        $status = null;
        if (isset($options['boards'])) {
            /** @var \Application\Entity\Board $boards */
            $board = $options['boards'];
            /** @var \Application\Entity\Project $project */
            $project = $board->getProject();
            /** @var \Application\Entity\User $administrator */
            $administrator = $board->getAdministrator();
            /** @var \Application\Entity\Status $status */
            $status = $board->getStatus();
        }

        $this->add(new Form\Element\Text('title', array(
            'label' => "Title"
        )));
        if (is_object($board)) {
            $this->get('title')->setValue($board->getTitle());
        }

        $this->add(new Form\Element\Text('code', array(
            'label' => "Code"
        )));
        if (is_object($board)) {
            $this->get('labels')->setValue($board->getCode());
        }

        $this->add(new Form\Element\Text('description', array(
            'label' => "Description"
        )));
        if (is_object($board)) {
            $this->get('description')->setValue($board->getDescription());
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
        if (is_object($board)) {
            $this->get('description')->setValue($board->getDescription());
        }

        $this->add(new Form\Element\Select('administrator', array(
            'label' => "Administrator",
            'value_options' => $administrators,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));

        if (is_object($administrator)) {
            $this->get('type')->setValue($administrator->getId());
        }


        $this->add(new Form\Element\Select('status', array(
            'label' => "Board status",
            'value_options' => $boards_status,
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
        $board = null;
        $project = null;
        $administrator = null;
        $status = null;
        if (isset($options['boards'])) {
            /** @var \Application\Entity\Board $boards */
            $board = $options['boards'];
            /** @var \Application\Entity\Project $project */
            $project = $board->getProject();
            /** @var \Application\Entity\User $administrator */
            $administrator = $board->getAdministrator();
            /** @var \Application\Entity\Status $status */
            $status = $board->getStatus();
        }

        return $factory->createInputFilter(array(
            'title' => array(
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
            'code' => array(
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
            'administrator' => array(
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
                            'callback' => function ($value, $context = array()) use ($administrator) {
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