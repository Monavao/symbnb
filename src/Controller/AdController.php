<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdController extends AbstractController
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
        $this->manager    = $manager; //Plus pour ajout /suppression en base
    }

    /**
     * @return Response
     */
    public function index()
    {
        $ads = $this->repository->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }


    public function show(Ad $ad, string $slug)
    {
        if ($ad->getSlug() !== $slug) {
            return $this->redirectToRoute('ads_show', [
                'id'   => $ad->getId(),
                'slug' => $ad->getSlug()
            ], 301);
        }

        $ad = $this->repository->findOneBySlug($slug);

        return $this->render('ad/show.html.twig', ['ad' => $ad]);
    }
}
