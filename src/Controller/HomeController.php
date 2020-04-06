<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @param string $prenom
     * @return Response
     */
    public function hello(string $prenom)
    {
        return $this->render('hello.html.twig', ['prenom' => $prenom]);
    }


    /**
     * @return Response
     */
    public function home()
    {
        $prenoms = ['Toto' => 31, 'Tata' => 70, 'Tutu' => 17];

        return $this->render('home.html.twig', [
            'title'   => 'Bonjour !!!!',
            'age'     => 21,
            'prenoms' => $prenoms
        ]);
    }
}
