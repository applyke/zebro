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

    /** @ORM\Column(type="string", length=128) */

    protected $min;
    
   /** @ORM\Column(type="string", length=128) */

    protected $max;

    /**
     * @ORM\ManyToOne(targetEntity="Board")
     * @ORM\JoinColumn(name="board", referencedColumnName="id")
     */
    protected $board;

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
