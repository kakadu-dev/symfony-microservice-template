<?php
//
//namespace App\Repository;
//
//use App\Entity\Country;
//use App\Entity\Demo;
//use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Persistence\ManagerRegistry;
//
///**
// * @method Demo|null find($id, $lockMode = null, $lockVersion = null)
// * @method Demo|null findOneBy(array $criteria, array $orderBy = null)
// * @method Demo[]    findAll()
// * @method Demo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
// */
//class DemoRepository extends ServiceEntityRepository
//{
//    public function __construct(ManagerRegistry $registry)
//    {
//        parent::__construct($registry, Demo::class);
//    }
//
//    // /**
//    //  * @return Country[] Returns an array of Country objects
//    //  */
//    /*
//    public function findByExampleField($value)
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//    */
//
//    /*
//    public function findOneBySomeField($value): ?Demo
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
//    */
//}
