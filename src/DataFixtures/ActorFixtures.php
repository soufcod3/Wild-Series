<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends Fixture
{

    public const FIRSTNAME = [
        'Mark',
        'Robert Downey',
        'Dwayne',
        'Bruce',
        'Edward',
        'Will',
        'Leonardo',
    ];

    public const LASTNAME = [
        'Wahlberg',
        'Junior',
        'Johnson',
        'Willis',
        'Norton',
        'Smith',
        'Di Caprio',
    ];

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        foreach (self::FIRSTNAME as $key => $firstName) {
            $actor = new Actor();
            $actor->setfirstName($firstName);
            $actor->setLastName(self::LASTNAME[$key]);
            $manager->persist($actor);
            // Adding a reference to each created actor object
            $this->addReference('actor_' . $key, $actor);
        }
        $manager->flush();
    }
}
