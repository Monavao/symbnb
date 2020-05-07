<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends AbstractController
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
     * @param Ad      $ad
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request): Response
    {
        $booking = new Booking();
        $form    = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $booking->setBooker($user)->setAd($ad);

            if (!$booking->isBookableDates()) {
                $this->addFlash('warning', "Les dates choisies ne sont pas disponibles");
            } else {
                $this->manager->persist($booking);
                $this->manager->flush();

                return $this->redirectToRoute('booking_show', ['id' => $booking->getId(), 'withAlert' => true]);
            }
        }

        return $this->render('booking/book.html.twig', [
            'ad'   => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Booking $booking
     * @param int     $id
     * @param Request $request
     * @return Response
     */
    public function show(Booking $booking, int $id, Request $request): Response
    {
        if ($booking->getId() !== $id) {
            return $this->redirectToRoute('home', [], 301);
        }

        $comment = new Comment();
        $form    = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAd($booking->getAd())
                    ->setAuthor($this->getUser());

            $this->manager->persist($comment);
            $this->manager->flush();

            $this->addFlash('success', 'Votre commentaire a bien été enregistré !');
        }

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'form'    => $form->createView()
        ]);
    }
}
