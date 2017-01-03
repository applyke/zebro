<?php

namespace Application\Form\Setting;

use Zend\Form\Element;
use Zend\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Application\Service\HydratorStrategyService;
use Application\Form\ApplicationFormAbstract;
use Zend\Validator\File\MimeType;

class IssuesPriorityForm extends ApplicationFormAbstract
{
    private $is_icon_required = true;

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new HydratorStrategyService);
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');
        $this->setAttribute('class', 'mdl-cell mdl-cell--6-col');

        $issuePriority = null;
        if (isset($options['globalIssuePriority'])) {
            /** @var \Application\Entity\GlobalIssuePriority $issuePriority */
            $issuePriority = $options['globalIssuePriority'];
        }

        $this->add(new Form\Element\Text('title', array(
            'label' => "Title"
        )));
        if (is_object($issuePriority)) {
            $this->get('title')->setValue($issuePriority->getTitle());
        }

        $this->add(new Form\Element\Text('code', array(
            'label' => "Code",
        )));

        if (is_object($issuePriority)) {
            $this->get('code')->setValue($issuePriority->getCode());
        }

        if (is_object($issuePriority) && $issuePriority->getIcon()) {
            $image = new Form\Element\Image('preview');
            $image->setAttribute('src', $issuePriority->getIcon() );
            $image->setAttribute('width', '50px');
            $this->add($image);
            $this->is_icon_required = false;
        }


        $this->add(new Form\Element\File('icon', array(
                'label' => "Icon",
            )
        ));


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
        $issuePriority = null;
        if (isset($options['globalIssuePriority'])) {
            /** @var \Application\Entity\GlobalIssuePriority $issuePriority */
            $issuePriority = $options['globalIssuePriority'];
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
            'icon'=> array(
                'required'   => $this->is_icon_required,
                'validators' => array(
                    new \Zend\Validator\File\IsImage(),
                    new \Zend\Validator\File\FilesSize( array(
                            'max'  => 10240000,
                        )
                    ),
                    new \Zend\Validator\File\Count(array(
                        'max'  => 1,
                    )),
                )
            ),
        ));
    }
}
