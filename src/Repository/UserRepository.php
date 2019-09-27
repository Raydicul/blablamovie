<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function getUsersWithVote()
    {
        $qb = $this->createQueryBuilder('u')
            ->innerJoin('u.votes', 'v')
            ->getQuery();

        return $qb->getResult();
    }
}
