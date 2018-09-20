<?php

namespace PageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{
    public function findPages($page = 1, $limit = 10)
    {
        $query = $this->createQueryBuilder('p');
        $query->setMaxResults($limit);
        $query->setFirstResult(($limit * $page) - $limit);

        return $query->getQuery()->getResult();
    }

    public function countPages()
    {
        $query = $this->createQueryBuilder('p')->select('count(p.id)');

        $result = null;
        try {
            $result = $query->getQuery()->getOneOrNullResult();
        } catch (\Exception $e) {}

        return $result ? array_shift($result) : 0;
    }
}