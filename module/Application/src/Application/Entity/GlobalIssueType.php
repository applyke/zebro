<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\GlobalIssueTypeRepository"))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`global_issue_type`")
 */
class GlobalIssueType extends EntityAbstract
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

}
