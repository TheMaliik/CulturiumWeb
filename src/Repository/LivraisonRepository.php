<?php

namespace App\Repository;

use App\Entity\Livraison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livraison>
 *
 * @method Livraison|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livraison[]    findAll()
 * @method Livraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livraison::class);
    }

    /**
     * Recherche les livraisons par terme de recherche.
     *
     * @param string $searchTerm Le terme de recherche
     * @return Livraison[] Un tableau d'objets Livraison correspondant à la recherche
     */
    public function findBySearchTerm(string $searchTerm): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.dateDeLivraison LIKE :searchTerm')
            ->orWhere('l.statut LIKE :searchTerm')
            ->orWhere('l.depot LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult();
    }
}
