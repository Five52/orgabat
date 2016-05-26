<?php
/**
 * Created by PhpStorm.
 * User: lcoue
 * Date: 24/05/2016
 * Time: 17:22
 */

namespace Orgabat\GameBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
 }
