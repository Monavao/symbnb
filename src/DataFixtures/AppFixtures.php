<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i= 1; $i <= 30; $i++) {
            $ad = new Ad();
            $ad->setTitle("Titre de l'annonce $i")
               ->setSlug("titre-de-l-annonce-$i")
               ->setCoverImage("http://placehold.it/1000x300")
               ->setIntroduction("Introduction de l'annonce $i")
               ->setContent("<p>Le contenu l'annonce</p>")
               ->setPrice(mt_rand(40, 500))
               ->setRooms(mt_rand(1, 6));

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
