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
     * @ORM\Column(type="string", length=32,nullable=true)
     */
    protected $create;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $invite_to_project;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $read;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $write;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $add_project_to_archive;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $disable_user_in_project;
 }

