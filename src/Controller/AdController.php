<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

//        $ad = $this->repository->findOneBySlug($slug); // Ne sert plus car on a déjà la bonne entité! (Cf: ParamConverter)

        return $this->render('ad/show.html.twig', ['ad' => $ad]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $ad   = new Ad();
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $this->manager->persist($image);
            }

            $this->manager->persist($ad);
            $this->manager->flush();

            $this->addFlash(
                'success', "Annonce <strong>{$ad->getTitle()}</strong> enregistrée !"
            );

            return $this->redirectToRoute('ads_show', [
                'id'   => $ad->getId(),
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Ad      $ad
     * @return Response
     */
    public function edit(Request $request, Ad $ad)
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $this->manager->persist($image);
            }

            $this->manager->persist($ad);
            $this->manager->flush();

            $this->addFlash(
                'success', "Annonce <strong>{$ad->getTitle()}</strong>Modifications enregistrée !"
            );

            return $this->redirectToRoute('ads_show', [
                'id'   => $ad->getId(),
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
