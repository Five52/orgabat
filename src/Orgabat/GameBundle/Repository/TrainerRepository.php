<?php

namespace Orgabat\GameBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Orgabat\GameBundle\Entity\Trainer;

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
            ->getOneOrNullResult()
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
    public function getWithoutSections() {
        $trainersWithSection = $this
            ->createQueryBuilder('t')
            ->join('t.sections', 's')
            ->getQuery()
            ->getResult()
        ;

        if ($trainersWithSection == null) {
            return $this->createQueryBuilder('t')
                ->getQuery()
                ->getResult()
            ;
        }
        return $this->createQueryBuilder('t')
            ->where('t not in (:trainersWithSections)')
            ->setParameter('trainersWithSections', $trainersWithSection)
            ->getQuery()
            ->getResult()
        ;
    }
}
