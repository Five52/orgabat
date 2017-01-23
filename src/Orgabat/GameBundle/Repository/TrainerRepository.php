<?php

namespace Orgabat\GameBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Orgabat\GameBundle\Entity\Trainer;

/**
 * TrainerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TrainerRepository extends EntityRepository
{
    public function getWithSections($id) {
        return $this->createQueryBuilder('t')
            ->where('t.id = :id')
            ->setParameter('id',$id)
            ->leftJoin('t.sections', 's')
            ->leftJoin('s.apprentices','a' )
            ->addSelect('s')
            ->addSelect('a')
            ->orderBy('s.id', 'asc')
            ->getQuery()
            ->getResult()
        ;
    }
    public function getSections() {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.sections', 's')
            ->addSelect('s')
            ->orderBy('s.id', 'asc')
            ->getQuery()
            ->getResult()
        ;
    }

}