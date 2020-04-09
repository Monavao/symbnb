<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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


    /**
     * @param Ad     $ad
     * @param string $slug
     * @param int    $id
     * @return RedirectResponse|Response
     */
    public function show(Ad $ad, string $slug, int $id)
    {
        if ($ad->getSlug() !== $slug || $ad->getId() !== $id) {
            return $this->redirectToRoute('ads_show', [
                'id'   => $ad->getId(),
                'slug' => $ad->getSlug()
            ], 301);
        }

//        $ad = $this->repository->findOneBySlug($slug); // Ne sert plus car on a déjà la bonne entité!

        return $this->render('ad/show.html.twig', ['ad' => $ad]);
    }
}
