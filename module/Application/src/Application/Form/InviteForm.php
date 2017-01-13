<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Application\Service\HydratorStrategyService;

class InviteForm extends ApplicationFormAbstract
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new HydratorStrategyService);
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');
        $this->setAttribute('class', 'mdl-cell mdl-cell--6-col');

        $this->add(new Form\Element\Email('email_1', array('label' => "User's Email")));
        $this->add(new Form\Element\Email('email_2', array('label' => "User's Email")));
        $this->add(new Form\Element\Email('email_3', array('label' => "User's Email")));



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
            'email_1' => array(
                'required' => false,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array(
                    new \Zend\Validator\EmailAddress(),
                )
            ),
            'email_2' => array(
                'required' => false,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array(
                    new \Zend\Validator\EmailAddress(),
                )
            ),
            'email_3' => array(
                'required' => false,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array(
                    new \Zend\Validator\EmailAddress(),
                )
            ),


        ));
    }
}