<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ca_ES');
        $users = [];

        // Crear 5 usuaris
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->email());
            $user->setName($faker->name());
            $user->setPassword($this->hasher->hashPassword($user, 'password123'));
            $manager->persist($user);
            $users[] = $user;
        }

        // Crear 20 productes
        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setTitle($faker->sentence(3));
            $product->setDescription($faker->paragraph(2));
            $product->setPrice($faker->randomFloat(2, 1, 500));
            $product->setImage('https://picsum.photos/seed/' . $faker->word() . '/400/300');
            $product->setCreatedAt(new \DateTime());
            $product->setOwner($users[array_rand($users)]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
