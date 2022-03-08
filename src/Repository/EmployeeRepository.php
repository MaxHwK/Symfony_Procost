<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Employee;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function findEmployeeByPage(int $page)
    {
        $queryb = $this->createQueryBuilder('e')
            ->addSelect('j')
            ->leftJoin('e.job', 'j')
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10);
        return $queryb->getQuery()->getResult();
    }

    public function countEmployees()
    {
        $queryb = $this->createQueryBuilder('e')
            ->select('count(e)');
        return $queryb->getQuery()->getOneOrNullResult();
    }

}
