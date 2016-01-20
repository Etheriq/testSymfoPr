<?php

namespace CTO\AppBundle\Entity\Repository;

use Carbon\Carbon;
use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Entity\DTO\StatisticsMastersFilterDTO;
use Doctrine\ORM\EntityRepository;

/**
 * PaidSalaryJobRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class PaidSalaryJobRepository extends EntityRepository
{
    /**
     * @param CtoUser $ctoUser
     * @param StatisticsMastersFilterDTO $filterMastersDTO
     * @return array
     */
    public function getStatisticsWithMastersFilters(CtoUser $ctoUser, StatisticsMastersFilterDTO $filterMastersDTO)
    {
        $qb = $this->createQueryBuilder("p")
            ->select("p, masterName, job")
            ->leftJoin("p.master", "masterName")
            ->leftJoin("p.carJob", "job")
            ->where("job.cto = :cto")->setParameter("cto", $ctoUser);
        if (count($filterMastersDTO->getMasters())) {
            $qb
                ->andWhere("p.master IN (:master)")->setParameter("master", array_values($filterMastersDTO->getMasters()->getValues()));
        }
        if ($filterMastersDTO->getDateFrom()) {
            $qb
                ->andWhere("job.jobDate >= :fromDate")->setParameter("fromDate", Carbon::createFromFormat("d.m.Y", $filterMastersDTO->getDateFrom())->startOfDay());
        }
        if ($filterMastersDTO->getDateTo()) {
            $qb
                ->andWhere("job.jobDate <= :toDate")->setParameter("toDate", Carbon::createFromFormat("d.m.Y", $filterMastersDTO->getDateTo())->startOfDay());
        }
        $qb
            ->orderBy("job.jobDate", "DESC")
            ->orderBy("job.id", "DESC");

        return $qb->getQuery()->getResult();
    }
}
