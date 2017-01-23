<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\ProjectRepository"))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`project`")
 */
class Project extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=128) */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Company")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    protected $company;

    /** Code of project */
    /** @ORM\Column(type="string", length=128) */
    protected $project_key;

    /** @ORM\Column(type="string", length=1024) */

    protected $description;

    /** Project's Logo */
    /** @ORM\Column(type="string", length=256) */
    protected $avatar;

//    /**  */
//    /** @ORM\Column(type="string", length=1024) */
//    protected $url;

    /**
     * @ORM\ManyToOne(targetEntity="ProjectType")
     * @ORM\JoinColumn(name="project_type", referencedColumnName="id")
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="ProjectCategories")
     * @ORM\JoinColumn(name="project_category", referencedColumnName="id")
     */
    protected $category;


    /**
     * @ORM\ManyToMany(targetEntity="Status", inversedBy="id")
     * @ORM\JoinTable(name="column_to_project")
     */
    protected $status;


    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="project_lead", referencedColumnName="id")
     */
    protected $project_lead;

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
