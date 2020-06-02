<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\Color;
use App\Entity\Model;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

class CarRepository extends EntityRepository
{
    /**
     * @return Car[]
     */
    public function getAppraisedList(int $page, int $itemPerPage): array
    {
        $qb = $this->createQueryBuilder('c');
        $firstResult = ($page - 1) * $itemPerPage;
        $qb
            ->select('c, a')
            ->innerJoin('c.appraisal', 'a')
            ->orderBy('c.updatedAt', 'desc')
            ->setFirstResult($firstResult)
            ->setMaxResults($itemPerPage);

        return $qb->getQuery()->getResult();
    }

    public function findAppraised(string $id): ?Car
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c, a')
            ->leftJoin('c.appraisal','a')
            ->where('c.id = :id')
            ->setParameter('id', $id);
        ;
        $result = $qb->getQuery()->getResult();

        return $result[0] ?? null;
    }
}