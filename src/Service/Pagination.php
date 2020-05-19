<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class Pagination
{
    private $twig;

    private $entityClass;

    private $limit = 10;

    private $currentPage = 1;

    private $manager;

    private $route;

    private $templatePath;

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $requestStack, string $templatePath)
    {
        $this->route        = $requestStack->getCurrentRequest()->attributes->get('_route');
        $this->manager      = $manager;
        $this->twig         = $twig;
        $this->templatePath = $templatePath;
    }

    public function getData()
    {
        if (empty($this->entityClass)) {
            throw new Exception('Entité non spécifiée - Utilisez setEntityClass() du service Pagination');
        }

        $offset     = ($this->currentPage * $this->limit) - $this->limit;
        $repository = $this->manager->getRepository($this->entityClass);

        return $repository->findBy([], [], $this->limit, $offset);
    }

    public function getPages()
    {
        if (empty($this->entityClass)) {
            throw new Exception('Entité non spécifiée - Utilisez setEntityClass() du service Pagination');
        }

        $repository = $this->manager->getRepository($this->entityClass);
        $total      = count($repository->findAll());

        return ceil($total / $this->limit);
    }

    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page'  => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }
}
