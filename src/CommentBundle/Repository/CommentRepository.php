<?php

namespace CommentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PageBundle\Entity\Page;

class CommentRepository extends EntityRepository
{
    public function findLastComments(Page $page, $limit = 20)
    {
        $query = $this->createQueryBuilder('c');
        $query->where('c.page = :page');
        $query->setParameter('page', $page);
        $query->orderBy('c.id', 'DESC');
        $query->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }

    public function findComments(Page $page, $pager = 1, $limit = 10)
    {
        $query = $this->createQueryBuilder('c');
        $query->where('c.page = :page')->setParameter('page', $page);
        $query->orderBy('c.id', 'DESC');
        $query->setMaxResults($limit);
        $query->setFirstResult(($limit * $pager) - $limit);

        return $query->getQuery()->getResult();
    }

    public function countComments(Page $page)
    {
        $query = $this->createQueryBuilder('c')->select('count(c.id)');
        $query->where('c.page = :page')->setParameter('page', $page);

        $result = null;
        try {
            $result = $query->getQuery()->getOneOrNullResult();
        } catch (\Exception $e) {}

        return $result ? array_shift($result) : 0;
    }
}