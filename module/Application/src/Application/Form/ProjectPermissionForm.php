<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Application\Service\HydratorStrategyService;
use Application\Form\ApplicationFormAbstract;

class ProjectPermissionForm extends ApplicationFormAbstract
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new HydratorStrategyService);
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');
        $this->setAttribute('class', 'mdl-cell mdl-cell--6-col permissionForm');
        $projectPermission = null;
        $project = null;
        $user = null;

        if (isset($options['projectPermission'])) {
            /** @var \Application\Entity\ProjectPermission $projectPermission */
            $projectPermission = $options['projectPermission'];
            $project = $projectPermission->getProject();
            $user = $projectPermission->getUser();
        }


        $this->add(new Form\Element\Hidden('project', array(
            'label' => "Project Name",
        )));
        $this->get('project')->setValue($project->getId());

        $this->add(new Form\Element\Hidden('user', array(
            'label' => "Users",
        )));
        $this->get('user')->setValue($user->getId());


        $this->add(new Form\Element\Checkbox('create_project', array(
            'label' => "Create Project",
            'checked_value' => 1,
            'unchecked_value' => 0
        )));

        if (is_object($projectPermission)) {
            $this->get('create_project')->setValue($projectPermission->getCreateProject());
        }

        $this->add(new Form\Element\Checkbox('invite_to_project', array(
            'label' => "Invite To Project",
            'checked_value' => 1,
            'unchecked_value' => 0
        )));

        if (is_object($projectPermission)) {
            $this->get('invite_to_project')->setValue($projectPermission->getInviteToProject());
        }

       $this->add(new Form\Element\Checkbox('read_project', array(
            'label' => "Read Project",
            'checked_value' => 1,
            'unchecked_value' => 0
        )));

        if (is_object($projectPermission)) {
            $this->get('read_project')->setValue($projectPermission->getReadProject());
        }

       $this->add(new Form\Element\Checkbox('write_project', array(
            'label' => "Write & Read",
            'checked_value' => 1,
            'unchecked_value' => 0
        )));

        if (is_object($projectPermission)) {
            $this->get('write_project')->setValue($projectPermission->getWriteProject());
        }

       $this->add(new Form\Element\Checkbox('disable_user_in_project', array(
            'label' => "Disable User In Project",
            'checked_value' => 1,
            'unchecked_value' => 0
        )));

        if (is_object($projectPermission)) {
            $this->get('disable_user_in_project')->setValue($projectPermission->getDisableUserInProject());
        }

      $this->add(new Form\Element\Checkbox('add_project_to_archive', array(
            'label' => "Add Project To Archive",
            'checked_value' => 1,
            'unchecked_value' => 0
        )));

        if (is_object($projectPermission)) {
            $this->get('add_project_to_archive')->setValue($projectPermission->getAddProjectToArchive());
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
        $projectPermission = null;
        if (isset($options['projectPermission'])) {
            /** @var \Application\Entity\ProjectPermission $projectPermission */
            $projectPermission = $options['projectPermission'];
        }

        return $factory->createInputFilter(array(
            'create_project' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    new \Zend\Validator\InArray(array('haystack' => array(0, 1))),
                )
            ),
            'invite_to_project' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    new \Zend\Validator\InArray(array('haystack' => array(0, 1))),
                )
            ),
            'read_project' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    new \Zend\Validator\InArray(array('haystack' => array(0, 1))),
                )
            ),
            'write_project' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    new \Zend\Validator\InArray(array('haystack' => array(0, 1))),
                )
            ),
            'add_project_to_archive' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    new \Zend\Validator\InArray(array('haystack' => array(0, 1))),
                )
            ),
            'disable_user_in_project' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    new \Zend\Validator\InArray(array('haystack' => array(0, 1))),
                )
            ),
        ));
    }
}
