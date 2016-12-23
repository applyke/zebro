<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\ProjectCategoriesRepository"))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`project_categories`")
 */
class ProjectCategories extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=128) */
    protected $name;

    /** @ORM\Column(type="string", length=1024) */

    protected $description;

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
