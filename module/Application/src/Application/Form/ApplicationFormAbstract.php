<?php

namespace Application\Form;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Zend\Form;
use Application\ApplicationTraits\DoctrineEntityManagerAwareTrait;

class ApplicationFormAbstract extends Form\Form
{
    use DoctrineEntityManagerAwareTrait;

    /** @var bool */
    public $showBackBtn = true;

    /** @var  string */
    protected $backBtnUrl;

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        if (isset($options['backBtnUrl'])) {
            $this->setBackBtnUrl($options['backBtnUrl']);
        }
    }

    public function getBackBtnUrl()
    {
        return $this->backBtnUrl;
    }

    /**
     * @param string $url
     */
    protected function setBackBtnUrl($url)
    {
        if (!is_string($url)) {
            throw new InvalidArgumentException;
        }
        $this->backBtnUrl = $url;
    }
}
