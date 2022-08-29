<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Group;
use App\Domain\Repository\GroupRepositoryInterface;
use App\Infrastructure\Entity\Gang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<\App\Infrastructure\Entity\Gang>
 *
 * @method Gang|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gang|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gang[]    findAll()
 * @method Gang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GangRepository extends ServiceEntityRepository implements GroupRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gang::class);
    }

    public function add(Gang $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Gang $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Gango[] Returns an array of Gango objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Gango
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(Group $group)
    {
        $this->_em->persist($group);
        $this->_em->flush();
    }

    /** @param Gang[] */
    public function deleteGroups(array $groups)
    {
        foreach ($groups as $group){
            $this->_em->remove($group);
        }
        $this->_em->flush();
    }

    public function deleteGroup(Gang $group)
    {
        $this->_em->remove($group);
        $this->_em->flush();
    }

    /** @return \App\Infrastructure\Entity\Gang[] */
    public function getQueue(): array
    {
        return $this->createQueryBuilder('g')
            ->where('g.car IS NULL')
            ->orderBy('g.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
