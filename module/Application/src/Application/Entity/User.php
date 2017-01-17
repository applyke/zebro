<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`user`")
 */
class User extends EntityAbstract
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=128, nullable=true) */
    protected $first_name;

    /** @ORM\Column(type="string", length=128, nullable=true) */
    protected $middle_name;

    /** @ORM\Column(type="string", length=128, nullable=true) */
    protected $company_account;

    /** @ORM\Column(type="string", length=128, nullable=true) */
    protected $last_name;

    /** @ORM\Column(type="string", length=255, unique=true) */
    protected $email;

    /** @ORM\Column(type="string", length=128) */
    protected $password;

    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    protected $role;

    /**
     * @ORM\ManyToMany(targetEntity="Company", inversedBy="id")
     * @ORM\JoinTable(name="user_to_company")
     */
    protected $companies;

    /** activity user */
    /** @ORM\Column(type="smallint") */
    protected $status = 0;

    /** @ORM\Column(type="datetime") */
    protected $created;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $updated;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $last_login;
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


    public function setPassword($pwd = '')
    {
        $this->password = self::cryptPassword($pwd);
        return $this;
    }

    /** @see \DoctrineModule\Authentication\Adapter\ObjectRepository::validateIdentity */
    public function getPassword()
    {
        return $this->password;
    }

    public static function cryptPassword($pwd = '')
    {
        return password_hash($pwd, PASSWORD_DEFAULT);
    }

}
