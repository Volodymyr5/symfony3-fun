<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Genus;
use Doctrine\ORM\EntityRepository;

/**
 * Class GenusRepository
 * @package AppBundle\Repository
 */
class GenusRepository extends EntityRepository
{
    /**
     * @return Genus[]
     */
    public function findAllPublishedOrderBySize()
    {
        return $this->createQueryBuilder('genus')
            ->andWhere('genus.isPublised = :isPublised')
            ->setParameter('isPublised', true)
            ->orderBy('genus.speciesCount', 'DESC')
            ->getQuery()
            ->execute();
    }
}