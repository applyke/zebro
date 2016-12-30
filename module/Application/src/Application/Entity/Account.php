<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

class Account extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;


}