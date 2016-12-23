<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\RoleRepository")
 * @ORM\Table(name="`role`")
 */
class Role extends EntityAbstract
{
    const ROLE_ANONYMOUS = 'anonymous';
    const ROLE_USER = 'user';
    const ROLE_EDITOR = 'editor';
    const ROLE_ADMIN = 'admin';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=32, unique=true) */
    protected $code;

    /** @ORM\Column(type="string", length=128) */
    protected $name;
}
