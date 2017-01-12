<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\BoardRepository"))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`board`")
 */
class Board extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=128) */
    protected $title;

    /** @ORM\Column(type="string", length=128, unique=true) */
    protected $code;

    /** @ORM\Column(type="string", length=1024) */
    protected $description;


    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="administrator", referencedColumnName="id")
     */
    protected $administrator;

//    /**
//     * @ORM\OneToMany(targetEntity="Project", mappedBy="id")
//     */
//    protected $shares;

    //TODO: Think about this field
    /** @ORM\Column(type="smallint") */
    protected $status;

    /** @ORM\Column(type="datetime") */
    protected $created;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $updated;


    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->created = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updated = new \DateTime();
    }
}
