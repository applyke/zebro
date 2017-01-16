<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Application\Service\HydratorStrategyService;

class SignupForm extends ApplicationFormAbstract
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new HydratorStrategyService);
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');
        $this->setAttribute('class', 'mdl-cell mdl-cell--6-col');
        $companies = array();
        foreach ($options['companies'] as $t) {
            $companies[$t->getId()] = $t->getName();
        }
        $user = null;
        $company = null;
        if (isset($options['user'])) {
            /** @var \Application\Entity\User $user */
            $user = $options['user'];
            /** @var \Application\Entity\Company $company */
            $company = $user->getCompanies();

        }

        $this->add(new Form\Element\Text('first_name', array(
            'label' => "First Name"
        )));

        if (is_object($user)) {
            $this->get('first_name')->setValue($user->getFirstName());
        }

        $this->add(new Form\Element\Text('middle_name', array(
            'label' => "Middle Name"
        )));

        if (is_object($user)) {
            $this->get('middle_name')->setValue($user->getMiddleName());
        }

        $this->add(new Form\Element\Text('last_name', array(
            'label' => "Last Name"
        )));

        if (is_object($user)) {
            $this->get('last_name')->setValue($user->getLastName());
        }


        $this->add(new Form\Element\Email('email', array(
            'label' => "Email"
        )));

        if (is_object($user)) {
            $this->get('email')->setValue($user->getEmail());
        }

        $this->add(new Form\Element\Password('password', array(
            'label' => "Password"
        )));


//        $this->add(new Form\Element\Select('companies', array(
//            'label' => "Company Name",
//            'value_options' => $companies,
//            'attributes' => array(
//                'field_icon_class' => 'fa fa-unlock'
//            )
//        )));
//        if (is_object($company)) {
//            $this->get('companies')->setValue($company->getId());
//        }
//
//        $this->add(new Form\Element\Text('new_company', array(
//            'label' => "Your company name"
//        )));


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
            'first_name' => array(
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
            'middle_name' => array(
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
            'last_name' => array(
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
            'new_company' => array(
                'required' => false,
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
            'password' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array(
                    new \Zend\Validator\StringLength(array(
                        'min' => 4,
                        'max' => 20
                    )),
                )
            ),
            'email' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array(
                    new \Zend\Validator\EmailAddress(),
                )
            ),

//            'companies' => array(
//                'required' => false,
//                'filters' => array(
//                    new \Zend\Filter\StringTrim(),
//                    new \Zend\Filter\Digits(),
//                ),
//                'validators' => array(
//                    array(
//                        'name' => 'Callback',
//                        'options' => array(
//                            'messages' => array(
//                                \Zend\Validator\Callback::INVALID_VALUE => 'Companies doesn\'t exists',
//                            ),
//                            'callback' => function ($value, $context = array()) use ($project) {
//                                $em = $this->getEntityManager();
//                                $companyRepository = $em->getRepository('\Application\Entity\Company');
//                                if (is_object($companyRepository->findOneById($value))) {
//                                    return true;
//                                }
//                                return false;
//                            },
//                        ),
//                    ),
//                ),
//            ),

        ));
    }
}