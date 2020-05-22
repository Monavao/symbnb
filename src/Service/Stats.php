<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class Stats
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getStats()
    {
        $users    = $this->getEntityCount('App\Entity\User');
        $ads      = $this->getEntityCount('App\Entity\Ad');
        $bookings = $this->getEntityCount('App\Entity\Booking');
        $comments = $this->getEntityCount('App\Entity\Comment');

        return compact('users', 'ads', 'bookings', 'comments');
    }

    /**
     * @param string $entity
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getEntityCount(string $entity)
    {
        return $this->manager->createQuery("SELECT COUNT(c) FROM {$entity} c")->getSingleScalarResult();
    }

    /**
     * @param string $order
     * @param int    $maxResult
     * @return int|mixed|string
     */
    public function getAdsStats(string $order, int $maxResult)
    {
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName
                    FROM App\Entity\Comment c
                    JOIN c.ad a
                    JOIN a.author u
                    GROUP BY a
                    ORDER BY note ' . $order
        )->setMaxResults($maxResult)->getResult();
    }
}
