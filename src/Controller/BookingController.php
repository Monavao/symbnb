<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends AbstractController
{
    /**
     * @param Request                $request
     * @param Ad                     $ad
     * @param EntityManagerInterface $manager
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function book(Request $request, Ad $ad, EntityManagerInterface $manager): Response
    {
        $booking = new Booking();
        $form    = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $booking->setBooker($user)
                    ->setAd($ad);

            $manager->persist($booking);
            $manager->flush();

            return $this->redirectToRoute('booking_show', ['id' => $booking->getId(), 'withAlert' => true]);
        }

        return $this->render('booking/book.html.twig', [
            'ad'   => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Booking $booking
     * @param int $id
     * @return Response
     */
    public function show(Booking $booking, int $id): Response
    {
        if ($booking->getId() !== $id) {
            return $this->redirectToRoute('home', [], 301);
        }

        return $this->render('booking/show.html.twig', [
            'booking' => $booking
        ]);
    }
}
