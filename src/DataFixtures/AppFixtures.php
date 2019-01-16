<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private  $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Elmardi')
            ->setLastName('YAHIA')
            ->setemail('mardi166@gmail.com')
            ->sethash($this->encoder->encodePassword($adminUser, 'password'))
            ->setPicture('images/lifo.png')
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('<p></p>', $faker->paragraphs(3)) . '</p>')
            ->addUserRole($adminRole);

        $manager->persist($adminUser);

        //add false users
        $users = [];

        $genders = ['male', 'female'];

        for ($i=1; $i < 10; $i++) { 

            $user = new User();

            $gender = $faker->randomElement($genders);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId =$faker->numberBetween(0, 99) . '.jpg';
             $picture .= ($gender == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($gender))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription( '<p>' . join('<p></p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($hash)
                ->setPicture($picture);

                $manager->persist($user);

                $users[] = $user;
            
        }

        //add false announces
        for ($i=1; $i < 30; $i++) 
        { 
            $ad = new Ad;

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 380);
            $intro = $faker->paragraph(3);
            $desc = '<p>' . join('<p></p>', $faker->paragraphs(5)) . '</p>';
            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($intro)
                ->setDescription($desc)
                ->setPrice(mt_rand(45, 180))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);

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
