<?php

namespace P\AdminBundle\Service;

class Notification
{
    private $em = null;
    public function __construct($em)
    {
        $this->em = $em;
    }

    public function getCount($level = null, $nkey = null)
    {
        $qb = $this->em->getRepository('PAdminBundle:Notification')->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ;
        if($level) {
            $qb->where("n.level = ${level}");
        }
        if($nkey) {
            $qb->leftJoin('n.category', 'c')
                ->where("c.nkey = '${nkey}'");
        }
        return $qb->getQuery()
            ->getSingleScalarResult();
    }

    public function getMessages($level = null, $nkey = null)
    {
        $qb = $this->em->getRepository('PAdminBundle:Notification')->createQueryBuilder('n');
        if($level) {
            $qb->where("n.level = ${level}");
        }
        if($nkey) {
            $qb->leftJoin('n.category', 'c')
                ->where("c.nkey = '${nkey}'");
        }
        return $qb->getQuery()
            ->getResult();
    }
}

