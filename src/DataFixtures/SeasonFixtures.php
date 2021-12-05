<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Season;

class SeasonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $season = new Season();
            $season->setProgram($this->getReference('program_' . $i));
            $season->setNumber($i);
            $season->setYear('200' . $i);
            $season->setDescription('Ceci est la description de la saison ' . $i);
            $manager->persist($season);
            $this->addReference('season' . $i, $season);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Return here all fixtures classes which SeasonFixtures depends on
        return [
            ProgramFixtures::class,
        ];
    }
}
