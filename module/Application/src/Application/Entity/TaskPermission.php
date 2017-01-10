<?php
//
//namespace Application\Entity;
//
//use Doctrine\ORM\Mapping as ORM;
//
//
///**
// * @ORM\Entity(repositoryClass="Application\Repository\TaskPermissionRepository"))
// * @ORM\HasLifecycleCallbacks
// * @ORM\Table(name="`task_permission`")
// */
//class TaskPermission extends EntityAbstract
//{
//    /**
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     * @ORM\Column(type="integer")
//     */
//    protected $id;
//
//    /**
//     * @ORM\ManyToOne(targetEntity="User")
//     * @ORM\JoinColumn(name="user_id", referencedColumnName="id") */
//    protected $user;
//
//    /**
//     * @ORM\ManyToOne(targetEntity="Project")
//     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")     */
//    protected $project;
//
//    /**
//     * @ORM\Column(type="boolean", nullable=true)
//     */
//    protected $create_task;
//
//    /**
//     * @ORM\Column(type="boolean", nullable=true)
//     */
//    protected $update_task;
//}
//
