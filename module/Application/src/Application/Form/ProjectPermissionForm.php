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
        $this->setAttribute('class', 'mdl-cell mdl-cell--6-col');

        $projectPermission = null;
        $companies_users = array();
        $companies_projects = array();
        $project = null;
        $user = null;
        if (isset($options['projectPermission'])) {
            /** @var \Application\Entity\ProjectPermission $projectPermission */
            $projectPermission = $options['projectPermission'];
            $project = $projectPermission->getProject();
            $user = $projectPermission->getUser();
        }

        foreach ($options['companies_users'] as $t) {
            $companies_users[$t->getId()] = $t->getName().' '. $t->getLastName();
        }


        foreach ($options['companies_projects'] as $t) {
            $companies_projects[$t->getId()] = $t->getName();
        }


        $this->add(new Form\Element\Select('project', array(
            'label' => "Project Name",
            'value_options' => $companies_projects,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));
        if (is_object($project)) {
            $this->get('project')->setValue($project->getId());
        }

        $this->add(new Form\Element\Select('user', array(
            'label' => "Users",
            'value_options' => $companies_users,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));
        if (is_object($user)) {
            $this->get('user')->setValue($user->getId());
        }


        $this->add(new Form\Element\Radio('create_task', array(
            'label' => "Create Task",
            'value_options' => array(
                '0' => 'False',
                '1' => 'True',
            ),
        )));
        if (is_object($projectPermission)) {
            $this->get('create_task')->setValue($projectPermission->getCreateTask());
        }

        $this->add(new Form\Element\Radio('update_task', array(
            'label' => "Update Task",
            'value_options' => array(
                '0' => 'False',
                '1' => 'True',
            ),
        )));
        if (is_object($projectPermission)) {
            $this->get('update_task')->setValue($projectPermission->getUpdateTask());
        }

        $this->add(new Form\Element\Radio('create_project', array(
            'label' => "Create Project",
            'value_options' => array(
                '0' => 'False',
                '1' => 'True',
            ),
        )));
        if (is_object($projectPermission)) {
            $this->get('create_project')->setValue($projectPermission->getCreateProject());
        }

        $this->add(new Form\Element\Radio('update_project', array(
            'label' => "Update Task",
            'value_options' => array(
                '0' => 'False',
                '1' => 'True',
            ),
        )));
        if (is_object($projectPermission)) {
            $this->get('update_project')->setValue($projectPermission->getUpdateTask());
        }

        $this->add(new Form\Element\Radio('invite_to_project', array(
            'label' => "Can Invite To Project",
            'value_options' => array(
                '0' => 'False',
                '1' => 'True',
            ),
        )));
        if (is_object($projectPermission)) {
            $this->get('invite_to_project')->setValue($projectPermission->getInviteToProject());
        }

        $this->add(new Form\Element\Radio('read_project', array(
            'label' => "Read Project",
            'value_options' => array(
                '0' => 'False',
                '1' => 'True',
            ),
        )));
        if (is_object($projectPermission)) {
            $this->get('read_project')->setValue($projectPermission->getReadProject());
        }

        $this->add(new Form\Element\Radio('delete_user_from_project', array(
            'label' => "Can Delete User From Project",
            'value_options' => array(
                '0' => 'False',
                '1' => 'True',
            ),
        )));
        if (is_object($projectPermission)) {
            $this->get('delete_user_from_project')->setValue($projectPermission->getDeleteUserFromProject());
        }

        $this->add(new Form\Element\Radio('add_project_to_archive', array(
            'label' => "Can Add Project To Archive",
            'value_options' => array(
                '0' => 'False',
                '1' => 'True',
            ),
        )));
        if (is_object($projectPermission)) {
            $this->get('add_project_to_archive')->setValue($projectPermission->getAddProjectToArchive());
        }

        $this->add(new Form\Element\Radio('change_permission', array(
            'label' => "Change Permission",
            'value_options' => array(
                '0' => 'False',
                '1' => 'True',
            ),
        )));
        if (is_object($projectPermission)) {
            $this->get('change_permission')->setValue($projectPermission->getChangePermission());
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

        return $factory->createInputFilter(array());
    }
}
