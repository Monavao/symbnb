<?php

namespace App\Controller\Admin;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAdController extends AbstractController
{
    /**
     * @var AdRepository
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

    public function __construct(AdRepository $repository, EntityManagerInterface $manager, Pagination $pagination)
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
        $this->pagination->setEntityClass(Ad::class)->setCurrentPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $this->pagination
        ]);
    }

    /**
     * @param Request $request
     * @param Ad      $ad
     * @return Response
     */
    public function edit(Request $request, Ad $ad): Response
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($ad);
            $this->manager->flush();
            $this->addFlash('success', "L'annonce <strong>{$ad->getId()}</strong> a bien été modifiée");
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad'   => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Ad      $ad
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request, Ad $ad): Response
    {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash('warning', "Vous ne pouvez supprimer l'annonce <strong>{$ad->getTitle()}</strong> ! Il y a déjà des réservations dessus");
        } elseif ($this->isCsrfTokenValid('delete' . $ad->getSlug(), $request->get('_token'))) {
            $this->manager->remove($ad);
            $this->manager->flush();

            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée");
        }

        return $this->redirectToRoute('admin_ads');
    }
}
