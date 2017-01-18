<?php

namespace Application\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class ErrorController extends AbstractController
{

    public function errorAction()
    {
       return new ViewModel(array());
    }

    public function error404Action()
    {
       return new ViewModel(array());
    }

}
