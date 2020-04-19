<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
//use Cocur\Slugify\Slugify;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $hash= $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName)
                 ->setLastName($faker->lastName)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' . implode('<p></p>', $faker->paragraphs(3)) . '</p>')
                 ->setPictureFile(new File('public/images/users/empty-avatar.png'))
                 ->setPicture('empty-avatar.png')
                 ->setHash($hash);

            $manager->persist($user);

            $users[] = $user;
        }

        for ($i = 1; $i <= 30; $i++) {
            $ad = new Ad();

            $publisher = $users[array_rand($users)];

            $ad->setTitle($faker->sentence())
               ->setCoverFile(new File('public/images/ads/empty.jpeg'))
               ->setCoverImage( "empty.jpeg")
               ->setIntroduction($faker->paragraph(2))
               ->setContent('<p>' . implode('<p></p>', $faker->paragraphs(5)) . '</p>')
               ->setPrice(mt_rand(40, 500))
               ->setRooms(mt_rand(1, 6))
               ->setAuthor($publisher);

            for ($j = 1; $j <= mt_rand(4, 8); $j++) {
                $image = new Image();
                $image->setUrlFile(new File('public/images/ads/empty.jpeg'))
                      ->setUrl("empty.jpeg")
                      ->setCaption($faker->sentence())
                      ->setAd($ad);

                $manager->persist($image);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
