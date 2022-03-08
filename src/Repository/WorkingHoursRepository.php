<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\WorkingHours;

/**
 * @method WorkingHours|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkingHours|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkingHours[]    findAll()
 * @method WorkingHours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkingHoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkingHours::class);
    }

    public function findAllValues($id, $page)
    {
        $queryb = $this->createQueryBuilder('m')
            ->addSelect('e')
            ->addSelect('p')
            ->leftJoin('m.employee', 'e')
            ->leftJoin('m.project', 'p')
            ->where('m.employee = :id')
            ->setParameter('id', $id)
            ->setFirstResult(($page - 1) * 5)
            ->orderBy('m.creationDate','DESC')
            ->setMaxResults(5);
        return $queryb->getQuery()->getResult();
    }

    public function findPersonValueByProject($id, $page)
    {
        $queryb = $this->createQueryBuilder('m')
            ->addSelect('e')
            ->addSelect('p')
            ->leftJoin('m.employee', 'e')
            ->leftJoin('m.project', 'p')
            ->where('m.project = :id')
            ->setParameter('id', $id)
            ->orderBy('m.creationDate','DESC')
            ->setFirstResult(($page - 1) * 5)
            ->setMaxResults(5);
        return $queryb->getQuery()->getResult();
    }

    public function findFiveLatestCreateInfos()
    {
        $queryb = $this->createQueryBuilder('m')
            ->orderBy('m.creationDate', 'DESC')
            ->setMaxResults(5);
        return $queryb->getQuery()->getResult();
    }

    public function findEmployeeByProject($id)
    {
        $queryb = $this->createQueryBuilder('m')
            ->select('(sum(m.nbHours)*e.dailyCost) as cost')
            ->innerJoin('m.employee', 'e')
            ->where('m.project = :id')
            ->setParameter('id', $id)
            ->groupBy('m.employee');
        return $queryb->getQuery()->getResult();
    }

    public function countAllHours()
    {
        $queryb = $this->createQueryBuilder('m')
            ->select('sum(m.nbHours) as allHours');
        return $queryb->getQuery()->getOneOrNullResult();
    }

    public function bestEmployee()
    {
        $queryb = $this->createQueryBuilder('m')
            ->select('(sum(m.nbHours)*e.dailyCost) as cost')
            ->addSelect('m as value')
            ->innerJoin('m.employee', 'e')
            ->groupBy('m.employee')
            ->orderBy('cost', 'DESC')
            ->setMaxResults(1);
        return $queryb->getQuery()->getOneOrNullResult();
    }

    public function countProjectByLine($id)
    {
        $queryb = $this->createQueryBuilder('m')
            ->select('count(m)')
            ->where('m.project = :id')
            ->setParameter('id', $id);
        return $queryb->getQuery()->getOneOrNullResult();
    }

    public function countEmployeeByLine($id)
    {
        $queryb = $this->createQueryBuilder('m')
            ->select('count(m)')
            ->where('m.employ = :id')
            ->setParameter('id', $id);
        return $queryb->getQuery()->getOneOrNullResult();
    }
    
}
