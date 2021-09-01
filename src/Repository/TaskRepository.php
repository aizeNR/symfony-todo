<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function paginate($page = 1, $filterByUser): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect( 'u')
            ->innerJoin('p.user', 'u');

        return (new Paginator($qb))->paginate($page);
    }

    public function getPaginateTasksForUser(User $user, int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect( 'u')
            ->innerJoin('p.user', 'u')
            ->where('u.id = :userId')
            ->setParameter(':userId', $user->getId());

        return (new Paginator($qb))->paginate($page);
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
