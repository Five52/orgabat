<?php

namespace Orgabat\GameBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getUsersByRole($role)
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . $role . '"%')
            ->orderBy('u.id', 'asc')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getUsersWithoutRole($role)
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles not like :role')
            ->setParameter('role', '%"' . $role . '"%')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Gives a username not used or add a number at the end of it.
     * @param string baseUsername
     * @return string the username not used yet
     */
    public function getNotUsedUsername($baseUsername)
    {
        $username = $baseUsername;
        $i = 1;
        while ($this->getByUsername($username) !== null) {
            $username = $baseUsername . ' ' . $i++;
        }
        return $username;
    }

    public function getByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
