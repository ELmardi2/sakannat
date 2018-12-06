<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('EN-en');

        for ($i=1; $i < 35; $i++) 
        { 
            $ad = new Ad;

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 380);
            $intro = $faker->paragraph(3);
            $desc = '<p>' . join('<p></p>', $faker->paragraphs(5)) . '</p>';

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($intro)
                ->setDescription($desc)
                ->setPrice(mt_rand(45, 180))
                ->setRooms(mt_rand(1, 5));

                for ($f=0; $f <= mt_rand(2, 5) ; $f++) { 
                    $image = new Image();
                    $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                    $manager->persist($image);
                }
            $manager->persist($ad);
        }
        $manager->flush();
    }
}
