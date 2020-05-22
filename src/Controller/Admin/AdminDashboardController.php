<?php

namespace App\Controller\Admin;

use App\Service\Stats;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminDashboardController extends AbstractController
{
    /**
     * @var Stats
     */
    protected $stats;

    public function __construct(Stats $stats)
    {
        $this->stats   = $stats;
    }

    /**
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function index(): Response
    {
        $stats    = $this->stats->getStats();
        $bestAds  = $this->stats->getAdsStats('DESC', 5);
        $worstAds = $this->stats->getAdsStats('ASC', 5);


        return $this->render('admin/dashboard/index.html.twig', [
            'stats'    => $stats,
            'bestAds'  => $bestAds,
            'worstAds' => $worstAds
        ]);
    }
}
