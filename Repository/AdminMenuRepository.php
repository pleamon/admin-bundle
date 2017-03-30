<?php

namespace P\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AdminMenuRepository extends EntityRepository
{
    public function getRoots()
    {
        $result = $this->getEntityManager()->createQueryBuilder('am')
            ->where('am.parent IS NULL')
            ->andWhere('am.enabled = 1')
            ->orderBy('am.sort', 'asc')
            ->getQuery()
            ->getResult()
            ;
        return $result;
    }
}

