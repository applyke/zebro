<?php

namespace Application\Form;

use Zend\Form;
use Zend\Form\Element;
use Zend\InputFilter\Factory as FilterFactory;

class AuthForm extends ApplicationFormAbstract
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/admin');
        $this->setAttribute('class', 'mdl-cell mdl-cell--6-col');
        $user = null;
        if (isset($options['user'])) {
            /** @var \Application\Entity\User $user */
            $user = $options['user'];
        }
        /**
         * Show password fields only for create user mode
         */
        $this->add(new Form\Element\Password('password', array(
            'label' => "Password"
        )));

        $this->add(new Form\Element\Text('email', array(
            'label' => "Email"
        )));


//       $config = $this->getServiceLocator()->get('Configuration');
//        $public_key = $config['recaptcha']['public_key'];
//        $private_key = $config['recaptcha']['private_key'];
//        $options['recaptcha_public_key'] = $public_key;
//        $options['recaptcha_private_key'] = $private_key;
//        $this->add(new \Application\Form\Element\Recaptcha('g-recaptcha-response',
//            array(
//                'label' => "g-recaptcha",
//                'options' => array(
//                    'public_key' => $public_key,
//                    'private_key' => $private_key
//                )
//            )
//        ));
        $this->setInputFilter($this->inputFilter($options));
    }

    protected function inputFilter($options)
    {
        $factory = new FilterFactory();
        $user = null;
        if (isset($options['user'])) {
            /** @var \Application\Entity\User $user */
            $user = $options['user'];
        }
        return $factory->createInputFilter(array(
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
//            'g-recaptcha-response' => array(
//                'required' => true,
//                'validators' => array(
//                    new \Application\Form\Validator\RecaptchaValidator($options['recaptcha_public_key'], $options['recaptcha_private_key'])
//                )
//            ),

            'email' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array()
            ),
           
        ));
    }
}
