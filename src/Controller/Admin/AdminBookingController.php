<?php

namespace App\Controller\Admin;

use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminBookingController extends AbstractController
{
    /**
     * @var BookingRepository
     */
    protected $repository;
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    public function __construct(BookingRepository $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager    = $manager;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $bookings = $this->repository->findAll();

        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $bookings
        ]);
    }
}
