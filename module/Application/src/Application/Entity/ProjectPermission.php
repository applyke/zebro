<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="Application\Repository\ProjectPermissionRepository"))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`project_permission`")
 */
class ProjectPermission extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id") */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")     */
    protected $project;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $create_task;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $update_task;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $create_project;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $update_project;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $invite_to_project;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $read_project;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $add_project_to_archive;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $delete_user_from_project;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $change_permission;
}

