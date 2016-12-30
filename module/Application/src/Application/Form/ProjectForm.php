<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Application\Service\HydratorStrategyService;

class ProjectForm extends ApplicationFormAbstract
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new HydratorStrategyService);
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');
        $this->setAttribute('class', 'mdl-cell mdl-cell--6-col');
        $projectTypes = array();
        foreach ($options['project_types'] as $t) {
            $projectTypes[$t->getId()] = $t->getTitle();
        }

        $projectCategories = array();
        foreach ($options['project_categories'] as $t) {
            $projectCategories[$t->getId()] = $t->getName();
        }

        $project_lead = array();
        foreach ($options['users'] as $t) {
            $project_lead[$t->getId()] = $t->getFirstName();
        }
        $project = null;
        $projectType = null;
        $projectCategory = null;
        $projectLead = null;
        if (isset($options['project'])) {
            /** @var \Application\Entity\Project $project */
            $project = $options['project'];
            /** @var \Application\Entity\ProjectType $projectType */
            $projectType = $project->getType();
            /** @var \Application\Entity\ProjectCategories $projectCategories */
            $projectCategory = $project->getCategory();
            $projectLead = $project->getProjectLead();
        }

        $this->add(new Form\Element\Text('name', array(
            'label' => "Name"
        )));
        if (is_object($project)) {
            $this->get('name')->setValue($project->getName());
        }

        $this->add(new Form\Element\Text('project_key', array(
            'label' => "Key"
        )));
        if (is_object($project)) {
            $this->get('project_key')->setValue($project->getProjectKey());
        }


        $this->add(new Form\Element\Select('project_lead', array(
            'label' => "Project Lead",
            'value_options' => $project_lead,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));
        if (is_object($projectLead)) {
            $this->get('project_lead')->setValue($projectLead->getId());
        }
        $this->add(new Form\Element\Textarea('description', array(
            'label' => "Description"
        )));
        if (is_object($project)) {
            $this->get('description')->setValue($project->getDescription());
        }

        $this->add(new Form\Element\Text('avatar', array(
            'label' => "Avatar"
        )));
        if (is_object($project)) {
            $this->get('avatar')->setValue($project->getAvatar());
        }

        $this->add(new Form\Element\Select('type', array(
            'label' => "Project Type",
            'value_options' => $projectTypes,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));

        if (is_object($projectType)) {
            $this->get('type')->setValue($projectType->getId());
        }

        $this->add(new Form\Element\Select('category', array(
            'label' => "Project Category",
            'value_options' => $projectCategories,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));

        if (is_object($projectCategory)) {
            $this->get('category')->setValue($projectCategory->getId());
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
        $project = null;
        $project_type = null;
        $project_category = null;
        $project_lead = null;

        if (isset($options['project'])) {
            /** @var \Application\Entity\Project $project */
            $project = $options['project'];
            /** @var \Application\Entity\ProjectType $project_type */
            $project_type = $project->getType();
            /** @var \Application\Entity\ProjectCategories $project_category */
            $project_category = $project->getCategory();
        }
        return $factory->createInputFilter(array(
            'name' => array(
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
            'project_key' => array(
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
            'avatar' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array(
                    new \Zend\Validator\StringLength(array(
                        'min' => 2,
                        'max' => 256
                    )),
                )
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
                            'callback' => function ($value, $context = array()) use ($project_type) {
                                $em = $this->getEntityManager();
                                $projectTypeRepository = $em->getRepository('\Application\Entity\ProjectType');
                                if (is_object($projectTypeRepository->findOneById($value))) {
                                    return true;
                                }
                                return false;
                            },
                        ),
                    ),
                ),
            ),
            'category' => array(
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
                                \Zend\Validator\Callback::INVALID_VALUE => 'Category doesn\'t exists',
                            ),
                            'callback' => function ($value, $context = array()) use ($project_category) {
                                $em = $this->getEntityManager();
                                $projectCategoryRepository = $em->getRepository('\Application\Entity\ProjectCategories');
                                if (is_object($projectCategoryRepository->findOneById($value))) {
                                    return true;
                                }
                                return false;
                            },
                        ),
                    ),
                ),
            ),
            'project_lead' => array(
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
                            'callback' => function ($value, $context = array()) use ($project_lead) {
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
        ));
    }
}
