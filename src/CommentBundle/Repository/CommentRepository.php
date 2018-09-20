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
}