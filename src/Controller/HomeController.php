<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @var AdRepository
     */
    protected $adRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(AdRepository $adRepository, UserRepository $userRepository)
    {
        $this->adRepository   = $adRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return Response
     */
    public function home(): Response
    {
        return $this->render('home.html.twig', [
            'ads'   => $this->adRepository->findBestAds(),
            'users' => $this->userRepository->findBestUsers()
        ]);
    }
}
