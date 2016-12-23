<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\StatusRepository"))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`status`")
 */
class Status extends EntityAbstract
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

    /**
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="id")
     * @ORM\JoinColumn(name="status_to_project")
     */
    protected $project;

}
