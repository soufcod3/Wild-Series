<?php

namespace App\DataFixtures;

use App\Form\CategoryType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Program;
use App\Service\Slugify;

class ProgramFixtures extends Fixture
{
    private $slugify;

    public function __construct(Slugify $slugify)
    {
        // Normal dependency injection in the constructor
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $program = new Program();
            // Generating a slug for setTitle
            $program->setTitle('Série ' . $i);
            // Setting the slug for Program object
            $program->setSlug($this->slugify->generate($program->getTitle()));
            $program->setSynopsis('Ca c\'est le synopsis de la série ' . $i);
            $program->setPoster('https://www.serieslike.com/img/shop_01.png');
            $program->setCategory($this->getReference('category_1'));
            $program->addActor($this->getReference('actor_' . $i));
            $manager->persist($program);
            $this->addReference('program_' . $i, $program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Return here all fixtures classes which ProgramFixtures depends on
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
