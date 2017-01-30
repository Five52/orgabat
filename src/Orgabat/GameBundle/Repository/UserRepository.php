<?php
/**
 * Created by PhpStorm.
 * User: lenaic
 * Date: 30/01/2017
 * Time: 15:28
 */

namespace Orgabat\GameBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getUsersByRole($role) {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . $role . '"%')
            ->orderBy('u.id', 'asc')
            ->getQuery()
            ->getResult()
        ;
    }
}