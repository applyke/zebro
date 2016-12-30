<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\IssueRepository"))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`issue`")
 */
class Issue extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")     */
    protected $project;

    /** task's name**/
    /** @ORM\Column(type="string", length=128) */
    protected $summary;

    /** @ORM\Column(type="string", length=1024) */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="IssueType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="IssuePriority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")     */
    protected $priority;


    /**
     * Who working with task now
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_to_assignee", referencedColumnName="id")
     */
    protected $assignee;


    /**
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")     */
    protected $status;


//    /**  */
//   /** @ORM\Column(type="string", length=256) */
//    protected $labels;


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
