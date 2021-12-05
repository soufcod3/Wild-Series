<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 1; $i <= 10; $i++) {
            $actor = new Actor();
            $actor->setName('Acteur ' . $i);
            $manager->persist($actor);
            // Adding a reference to each created actor object
            $this->addReference('actor_' . $i, $actor);
        }
        $manager->flush();
    }
}
