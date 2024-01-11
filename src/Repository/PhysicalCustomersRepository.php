<?php

namespace App\Repository;

use App\Entity\CustomerPhysicals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerPhysicals>
 *
 * @method CustomerPhysicals|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerPhysicals|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerPhysicals[]    findAll()
 * @method CustomerPhysicals[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhysicalCustomersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerPhysicals::class);
    }

//    /**
//     * @return CustomerPhysicals[] Returns an array of CustomerPhysicals objects
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

//    public function findOneBySomeField($value): ?CustomerPhysicals
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
