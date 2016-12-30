<?php

namespace Application\Form\Setting;

use Zend\Form\Element;
use Zend\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Application\Service\HydratorStrategyService;
use Application\Form\ApplicationFormAbstract;

class ProjectTypeForm extends ApplicationFormAbstract
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new HydratorStrategyService);
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');
        $this->setAttribute('class', 'mdl-cell mdl-cell--6-col');

        $projectCategories = null;
        if (isset($options['projectCategories'])) {
            /** @var \Application\Entity\ProjectCategories $projectCategories */
            $projectCategories = $options['projectCategories'];
        }

        $this->add(new Form\Element\Text('title', array(
            'label' => "Title"
        )));
        if (is_object($projectCategories)) {
            $this->get('title')->setValue($projectCategories->getTitle());
        }

        $this->add(new Form\Element\Text('code', array(
            'label' => "Code"
        )));
        if (is_object($projectCategories)) {
            $this->get('code')->setValue($projectCategories->getCode());
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
        $projectCategories = null;
        if (isset($options['projectCategories'])) {
            /** @var \Application\Entity\ProjectCategories $projectCategories */
            $projectCategories = $options['projectCategories'];
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
                        'max' => 1024
                    )),
                )
            ),
        ));
    }
}
