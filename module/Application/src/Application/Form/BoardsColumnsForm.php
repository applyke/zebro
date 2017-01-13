<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Application\Service\HydratorStrategyService;

class BoardsColumnsForm extends ApplicationFormAbstract
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new HydratorStrategyService);
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');
        $this->setAttribute('class', 'mdl-cell mdl-cell--6-col');
        $boards = array();
        foreach ($options['board'] as $t) {
            $boards[$t->getId()] = $t->getTitle();
        }

        $boards_columns_status = array();
        foreach ($options['status'] as $t) {
            $boards_columns_status[$t->getId()] = $t->getTitle();
        }
        $board = null;
        $boardColumns = null;
        $status = null;
        if (isset($options['boardColumns'])) {
            /** @var \Application\Entity\BoardsColumns $boardColumns */
            $boardColumns = $options['boardColumns'];
            /** @var \Application\Entity\Board $board */
            $board = $boardColumns->getBoard();
            /** @var \Application\Entity\Status $status */
            $status = $boardColumns->getStatus();
        }

        if (!$board && $options['boards_from_id']) {
            $board = $options['boards_from_id'];
        }

        $this->add(new Form\Element\Text('name', array(
            'label' => "Column's Name"
        )));
        if (is_object($boardColumns)) {
            $this->get('name')->setValue($boardColumns->getName());
        }

        $this->add(new Form\Element\Text('min', array(
            'label' => "Min"
        )));
        if (is_object($boardColumns)) {
            $this->get('min')->setValue($boardColumns->getMin());
        }

        $this->add(new Form\Element\Text('max', array(
            'label' => "Max"
        )));
        if (is_object($boardColumns)) {
            $this->get('max')->setValue($boardColumns->getMax());
        }

        $this->add(new Form\Element\Select('board', array(
            'label' => "Board",
            'value_options' => $boards,
            'attributes' => array(
                'field_icon_class' => 'fa fa-unlock'
            )
        )));

        if (is_object($board)) {
            $this->get('board')->setValue($board->getId());
        }


        $this->add(new Form\Element\Select('status', array(
            'label' => "Boards column status",
            'value_options' => $boards_columns_status,
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
        $boardColumns = null;
        $status = null;
        if (isset($options['boardColumns'])) {
            /** @var \Application\Entity\BoardsColumns $boardColumns */
            $boardColumns = $options['boardColumns'];
            /** @var \Application\Entity\Board $board */
            $board = $boardColumns->getBoard();
            /** @var \Application\Entity\Status $status */
            $status = $boardColumns->getStatus();
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
            'min' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    new \Zend\Validator\StringLength(array(
                        'min' => 1,
                        'max' => 128
                    )),
                )
            ),
            'max' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    new \Zend\Validator\StringLength(array(
                        'min' => 1,
                        'max' => 128
                    )),
                )
            ),
            'board' => array(
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
                                \Zend\Validator\Callback::INVALID_VALUE => 'Board doesn\'t exists',
                            ),
                            'callback' => function ($value, $context = array()) use ($board) {
                                $em = $this->getEntityManager();
                                $boardRepository = $em->getRepository('\Application\Entity\Board');
                                if (is_object($boardRepository->findOneById($value))) {
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