<?php

namespace App\Repository;

use App\DTO\PaginatorDTO;
use App\DTO\Task\TaskFilterDTO;
use App\Entity\Task;
use App\Entity\User;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function getPaginateTasksForUser(User $user, TaskFilterDTO $filter, PaginatorDTO $paginatorDTO): Paginator
    {
        $qb = $this->createQueryBuilder('t')
            ->addSelect( 'u')
            ->innerJoin('t.user', 'u')
            ->where('u.id = :userId')
            ->setParameter(':userId', $user->getId());

        $this->appendFilter($qb, $filter);

        return (new Paginator($qb, $paginatorDTO->getLimit()))->paginate($paginatorDTO->getPage());
    }

    private function appendFilter(QueryBuilder $queryBuilder, TaskFilterDTO $filterDTO)
    {
        if (!is_null($filterDTO->getTaskTitle())) {
            $queryBuilder->andWhere("t.title LIKE :taskTitle")
                ->setParameter('taskTitle', '%' . $filterDTO->getTaskTitle() . '%');
        }

        if (!is_null($filterDTO->getTaskStatus())) {
            $queryBuilder->andWhere("t.status = :taskStatus")
                ->setParameter('taskStatus',  $filterDTO->getTaskStatus());
        }
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
