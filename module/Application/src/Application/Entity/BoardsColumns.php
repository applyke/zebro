<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\BoardsColumnsRepository"))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`boards_columns`")
 */
class BoardsColumns extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=128) */
    protected $name;

    /** min count of elements in column */
    /** @ORM\Column(type="string", length=128) */
    protected $min;

    /** max count of elements in column */
    /** @ORM\Column(type="string", length=128) */
    protected $max;
    
 
    /**
     * @ORM\ManyToOne(targetEntity="Board")
     * @ORM\JoinColumn(name="board", referencedColumnName="id")
     */
    protected $board;

    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="id")
     * @ORM\JoinTable(name="column_to_project")
     */
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
