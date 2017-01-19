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


    /** @ORM\Column(type="smallint") */
    protected $create_project = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $invite_to_project;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $read_project=1;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $write_project;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $add_project_to_archive;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $disable_user_in_project;
 }

