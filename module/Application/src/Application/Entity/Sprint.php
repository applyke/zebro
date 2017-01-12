<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\SprintRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`sprint`")
 */
class Sprint extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=128) */
    protected $title;

    /** @ORM\Column(type="string", length=1024, nullable=true) */
    protected $description;

    /** @ORM\Column(type="integer") */
    protected $hours;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;
}