<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Project;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findCostByProject(): array
    {
        $queryb = $this->createQueryBuilder('p')
            ->select('p as project')
            ->leftJoin('p.timesList', 'm')
            ->leftJoin('m.employee', 'e')
            ->addSelect('e,m ,sum(m.nbHours*e.dailyCost) as total')
            ->groupBy('p.id')
            ->orderBy('p.creationDate', 'DESC')
            ->setMaxResults(5);

        return $queryb->getQuery()->getResult();
    }

    public function finishCountProject()
    {
        $queryb = $this->createQueryBuilder('p')
            ->select('count(p)')
            ->where('p.deliveryDate IS NOT NULL');

        return $queryb->getQuery()->getOneOrNullResult();
    }

    public function projectListFinish()
    {
        $queryb = $this->createQueryBuilder('p')
            ->select('p as project')
            ->leftJoin('p.timesList', 'm')
            ->leftJoin('m.employee', 'e')
            ->addSelect('e,m ,sum(m.nbHours*e.dailyCost) as total')
            ->where('p.deliveryDate IS NOT NULL')
            ->groupBy('p.id')
            ->orderBy('p.creationDate', 'DESC')
            ->setMaxResults(5);

        return $queryb->getQuery()->getResult();
    }

    public function notFinishCountProject()
    {
        $queryb = $this->createQueryBuilder('p')
            ->select('count(p)')
            ->where('p.deliveryDate IS NULL');
        return $queryb->getQuery()->getOneOrNullResult();
    }

    public function findProjectByPage(int $page)
    {
        $queryb = $this->createQueryBuilder('p')
            ->setFirstResult(($page - 1)*10)
            ->setMaxResults(10);
        return $queryb->getQuery()->getResult();
    }

    public function countProjects()
    {
        $queryb = $this->createQueryBuilder('p')
            ->select('count(p)');
        return $queryb->getQuery()->getOneOrNullResult();
    }
    
}
