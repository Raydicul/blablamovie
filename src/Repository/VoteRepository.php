<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class VoteRepository extends EntityRepository
{
    /**
     * Retourne le film le mieux noté de tous les temps
     *
     * @return mixed
     */
    public function getTop()
    {
        $qb = $this->createQueryBuilder('v')
            ->select('v.imdbID, count(v.imdbID) as counter')
            ->groupby('v.imdbID')
            ->orderBy('counter', 'desc')
            ->setMaxResults(1)
            ->getQuery();

        return $qb->getResult();
    }

    /**
     * Retourne le film le mieux noté dans un interval de temps
     *
     * @param $startDate
     * @param $endDate
     * @return mixed
     * @throws \Exception
     */
    public function getTopFromDateRange($startDate, $endDate)
    {
        try {
            $stDate = new \DateTime($startDate);
            $edDate = new \DateTime($endDate);

            $qb = $this->createQueryBuilder('v')
                ->select('v.imdbID, count(v.imdbID) as counter')
                ->where('v.creationDate > :stDate')
                ->andWhere('v.creationDate < :edDate')
                ->groupby('v.imdbID')
                ->orderBy('counter', 'desc')
                ->setMaxResults(1)
                ->setParameter('stDate', $stDate)
                ->setParameter('edDate', $edDate)
                ->getQuery();

            return $qb->getResult();
        } catch (\Exception $e) {
            throw new \Exception('Erreur sur les dates...');
        }
    }
}
