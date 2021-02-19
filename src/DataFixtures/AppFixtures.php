<?php

namespace App\DataFixtures;

use App\Entity\Ad;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        $users= [];
        $genres = ['male','female'];
//gere utilisateures
        for($i=1; $i <=10; $i++){
            $user =new User();

            $genre = $faker->randomElement($genres);

            $picture ='https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99).'.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') .$pictureId;
            $hash= $this->encoder->encodePassword($user,'password');

            $user->setFirstname($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>'.join('</p><p>',$faker->paragraphs(3)).'</p>')
                ->setHash($hash)
            ->setPicture($picture);
            $manager->persist($user);
            $users[]=$user;
        }

        for ($i = 1; $i <= 30; $i++) {
            $ad = new Ad();

            $title =$faker->sentence();

            $coverImage = $faker->imageUrl(1000,350);
            $introduction =$faker->paragraph(2);
            $contente = 'p' . join('</p><p>',$faker->paragraphs(5)).'</p>';

            $user = $users[mt_rand(0, count($users)-1)];
            $ad->setTitre($title)

                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContente($contente)
                ->setPrice(mt_rand(100,500))
                ->setRooms(mt_rand(1,5))
            ->setAuthor($user);
            // $product = new Product();
            // $manager->persist($product);
            for($j = 1; $j <= mt_rand(2,5); $j++){
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
