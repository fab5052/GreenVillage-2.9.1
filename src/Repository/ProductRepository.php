<?php

namespace App\Repository;

use ApiPlatform\Symfony\EventListener\JsonApi\TransformPaginationParametersListener;
use App\Entity\Product;
// use App\Repository\Paginator;
use Knp\Bundle\PaginatorBundle\DependencyInjection\KnpPaginatorExtension;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Config\KnpPaginatorConfig;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


    // public function searchByLabel(string $query): array
    // {
    //     return $this->createQueryBuilder('p')
    //         ->where('p.label LIKE :query')
    //         ->setParameter('query', '%' . $query . '%')
    //         ->getQuery()
    //         ->getResult();
    // }

//     public function getAllPaginated(int $page = 1, int $limit = 10): array
// {
//     $offset = ($page - 1) * $limit;
//     $productsQuery = $this->createQueryBuilder('p')
//         ->orderBy('p.id', 'DESC')
//         ->setFirstResult($offset)
//         ->setMaxResults($limit);
            
//     $paginator = new KnpPaginatorConfig($productsQuery);
//     $data = $paginator->getProductsQuery()->getResult();
//     $result['products'] = $data;
//     $result['pages'] = ceil($paginator->count() / $limit);
//     $result['current'] = $page;
//     return $result;
// }


//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
