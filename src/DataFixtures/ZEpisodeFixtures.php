<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Episode;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Service\Slugify;

class ZEpisodeFixtures extends Fixture
{

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $episode = new Episode();
            $episode->setTitle('Episode auto ' . $i);
            $episode->setSlug($this->slugify->generate($episode->getTitle()));
            $episode->setNumber($i);
            $episode->setSynopsis('Hey ' . $i);
            $episode->setSeason($this->getReference('season' . $i));
            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
