<?php

namespace App\Controller\Admin;

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

    public function __construct(AdRepository $repository)
    {
        $this->repository = $repository;
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
}
