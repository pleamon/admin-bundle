<?php

namespace P\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    public function getCount($level = null, $nkey = null)
    {
        $qb = $this->getEntityManager()->getRepository('PAdminBundle:Notification')->createQueryBuilder('n')
            ->select('COUNT(n.id) as notification_count')
            ;
        if($level) {
            $qb->where("n.level = ${level}");
        }
        if($nkey) {
            $qb->leftJoin('n.category', 'c')
                ->andWhere("c.nkey = :nkey")
                ->setParameter('nkey', $nkey);
        }
        return $qb->getQuery()
            ->getSingleScalarResult();
    }

    public function getMessages($level = null, $nkey = null)
    {
        $qb = $this->getEntityManager()->getRepository('PAdminBundle:Notification')->createQueryBuilder('n');
        if($level) {
            $qb->where("n.level = ${level}");
        }
        if($nkey) {
            $qb->leftJoin('n.category', 'c')
                ->andWhere("c.nkey = :nkey")
                ->setParameter('nkey', $nkey);
        }
        return $qb->getQuery()
            ->getResult();
    }
}
