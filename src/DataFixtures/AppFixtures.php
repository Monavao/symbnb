<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 30; $i++) {
            $ad = new Ad();
            $ad->setTitle($faker->sentence())
               ->setCoverImage('https://picsum.photos/1000/350?image='.$i)
               ->setIntroduction($faker->paragraph(2))
               ->setContent('<p>' . implode('<p></p>', $faker->paragraphs(5)) . '</p>')
               ->setPrice(mt_rand(40, 500))
               ->setRooms(mt_rand(1, 6));

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setUrl('https://picsum.photos/640/480?image='.$j)
                      ->setCaption($faker->sentence())
                      ->setAd($ad);

                $manager->persist($image);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
