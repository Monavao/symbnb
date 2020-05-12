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
     * @return Response
     */
    public function index(): Response
    {
        $ads = $this->repository->findAll();

        return $this->render('admin/ad/index.html.twig', [
            'ads' => $ads
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
}
