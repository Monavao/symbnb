<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminDashboardController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function index(): Response
    {
        $users    = $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
        $ads      = $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\Ad u')->getSingleScalarResult();
        $bookings = $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\Booking u')->getSingleScalarResult();
        $comments = $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\Comment u')->getSingleScalarResult();

//        $bestAds = $this->manager->createQuery(
//            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName'
//        );
//        dump($users);
//        die;

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => compact('users', 'ads', 'bookings', 'comments')
        ]);
    }
}
