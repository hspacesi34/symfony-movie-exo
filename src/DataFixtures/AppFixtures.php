<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\{Category, Director, Movie, User};

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $categories = [];
        $users = [];
        $directors = [];

        for ($i = 0; $i < 10; $i++) {
            $cat = (new Category())
                ->setNameCat($faker->unique()->word());
            $categories[] = $cat;
            $manager->persist($cat);
        }

        for ($i = 0; $i < 10; $i++) {
            $user = (new User())
                ->setNameUser($faker->lastName())
                ->setFirstNameUser($faker->firstName())
                ->setEmailUser($faker->unique()->email())
                ->setPasswordUser($faker->password(6, 100))
                ->setCreatedAt(new \DateTimeImmutable());
            $users[] = $user;
            $manager->persist($user);
        }

        for ($i = 0; $i < 10; $i++) {
            $director = (new Director())
                ->setNameDirector($faker->lastName())
                ->setFirstnameDirector($faker->firstName())
                ->setDayOfBirth(new \DateTimeImmutable($faker->date('Y-m-d', '2000-01-01')))
                ->setCountryDirector($faker->country());
            $directors[] = $director;
            $manager->persist($director);
        }

        for ($i = 0; $i < 10; $i++) {
            $movie = (new Movie())
                ->setTitleMovie($faker->sentence())
                ->setSynopsisMovie($faker->paragraphs('3', true))
                ->setImageCover($faker->imageUrl())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUser($users[rand(0, (count($users) - 1))]);
            $randomDirectors = $faker->randomElements($directors, null, false);
            foreach ($randomDirectors as $director) {
                $movie->addDirector($director);
            }
            $randomCategories = $faker->randomElements($categories, null, false);
            foreach ($randomCategories as $category) {
                $movie->addCategory($category);
            }
            $manager->persist($movie);
        }

        $manager->flush();
    }
}
