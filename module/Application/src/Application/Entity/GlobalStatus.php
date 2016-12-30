<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\GlobalStatusRepository"))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`global_status`")
 */
class GlobalStatus extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=32, unique=true) */
    protected $code;

    /** @ORM\Column(type="string", length=128) */
    protected $title;

}
