<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\IssuePriorityRepository"))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`issue_priority`")
 */
class IssuePriority extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=128) */
    protected $title;

    /** @ORM\Column(type="string", length=128) */
    protected $code;

    /** @ORM\Column(type="string", length=256) */
    protected $icon;

}
