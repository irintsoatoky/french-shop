<?php

namespace App\Repository;

use App\Classe\Search;

use App\Entity\Product;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Requette qui permet de récupérer les produits en fonction de la recherche de l'utilisateur
     * @return Product[] Returns an array of Product objects
     */
    public function findWithSearch(Search $search)
    {
        if( !empty($search->categories) && !empty($search->string)){
            $query = $this->createQueryBuilder('p')
                            ->select('c','p')
                            ->join('p.category','c')
                            ->andWhere('(c.id IN (:categories) OR p.name LIKE :string)')
                            // sans () is string - avec () pour categoreis
                            ->setParameter('categories', $search->categories)
                            ->setParameter('string',"%{$search->string}%")
            ;
        }elseif(!empty($search->categories)){
            $query = $this->createQueryBuilder('p') // Alias doctrine de Product
                          ->select('c','p')
                          ->join('p.category','c')
                          ->andWhere('c.id IN (:categories)') // PArametre a déf avec setParameter
                          ->setParameter('categories', $search->categories)
            ;
        }elseif(!empty($search->string)){
            $query = $this->createQueryBuilder('p')
                          ->select('c','p')
                          ->join('p.category','c')
                          ->andWhere('p.name LIKE :string')
                          ->setParameter('string',"%{$search->string}%")
            ;
        }


        return $query->getQuery()->getResult();
    }
    
    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
