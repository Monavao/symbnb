<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    /**
     * @var Pagination
     */
    protected $pagination;

    public function __construct(BookingRepository $repository, EntityManagerInterface $manager, Pagination $pagination)
    {
        $this->repository = $repository;
        $this->manager    = $manager;
        $this->pagination = $pagination;
    }

    /**
     * @param int $page
     * @return Response
     */
    public function index(int $page): Response
    {
        $this->pagination->setEntityClass(Booking::class)->setCurrentPage($page);

        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $this->pagination
        ]);
    }

    /**
     * @param Request $request
     * @param Booking $booking
     * @return Response
     */
    public function edit(Request $request, Booking $booking): Response
    {
        $form = $this->createForm(AdminBookingType::class, $booking, [
//            'validation_groups' => ["Default"/*, "Front"*/] // Fait directement dans le AdminBookingType
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);
//            if (!$booking->isBookableDates()) {
//                $this->addFlash('warning', "Les dates choisies ne sont pas disponibles");
//
//                return $this->redirectToRoute('admin_bookings_edit', [
//                    'id' => $booking->getId()
//                ]);
//            }

            $this->manager->flush();
            $this->addFlash('success', "La réservation {$booking->getId()} a bien été modifiée");

            return $this->redirectToRoute('admin_bookings');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form'    => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * @param Request $request
     * @param Booking $booking
     * @return Response
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete_booking' . $booking->getId(), $request->get('_token'))) {
            $this->manager->remove($booking);
            $this->manager->flush();

            $this->addFlash('success', "Annonce supprimée");
        }

        return $this->redirectToRoute('admin_bookings');
    }
}
