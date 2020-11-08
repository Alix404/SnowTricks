<?php

namespace App\Repository;

use App\Entity\Trick;
use App\Entity\TrickSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    public function countTricks(TrickSearch $search)
    {
        $query = $this->createQueryBuilder('t')
            ->select('count(t.id)');
        if ($search->getCategory()) {
            $query = $query
                ->where('t.category = :category_id')
                ->setParameter('category_id', $search->getCategory());
        }
        $query
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findWithOrder($first, $max, TrickSearch $search)
    {
        $query = $this->createQueryBuilder('t');

        if ($search->getCategory()) {
            $query = $query
                ->where('t.category = :category_id')
                ->setParameter('category_id', $search->getCategory());
        }
        $query = $query
            ->orderBy('t.updated_at', 'DESC')
            ->setMaxResults($max)
            ->setFirstResult($first);

        return $query
            ->getQuery()
            ->getResult();
    }
}
