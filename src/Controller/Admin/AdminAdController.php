<?php

namespace App\Controller\Admin;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
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

    public function __construct(AdRepository $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager    = $manager;
    }

    /**
     * @param int $page
     * @return Response
     */
    public function index(int $page = 1): Response
    {
        $limit = 10;
        $start = ($page * $limit) - $limit;
        $total = count($this->repository->findAll());
        $pages = ceil($total / $limit);
        $ads   = $this->repository->findBy([], [], $limit, $start);

        return $this->render('admin/ad/index.html.twig', [
            'ads'   => $ads,
            'pages' => $pages,
            'page'  => $page
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
            $this->addFlash('warning', "Vous ne pouvez supprimer l'annonce <strong>{$ad->getTitle()}</strong> ! Il y a déjà réservations dessus");
        } elseif ($this->isCsrfTokenValid('delete' . $ad->getSlug(), $request->get('_token'))) {
            $this->manager->remove($ad);
            $this->manager->flush();

            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée");
        }

        return $this->redirectToRoute('admin_ads');
    }
}
