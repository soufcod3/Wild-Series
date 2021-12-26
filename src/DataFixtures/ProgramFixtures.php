<?php

namespace App\DataFixtures;

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

            $program = new Program();
            // Generating a slug for setTitle
            $program->setTitle('Série 1');
            // Setting the slug for Program object
            $program->setSlug($this->slugify->generate($program->getTitle()));
            $program->setSynopsis('Ca c\'est le synopsis de la série 1');
            $program->setPoster('https://www.serieslike.com/img/shop_01.png');
            $program->setCategory($this->getReference('category_1'));
            $program->addActor($this->getReference('actor_1'));
            $program->setOwner($this->getReference('contributor'));
            $manager->persist($program);
            $this->addReference('program_1', $program);
        
            $program = new Program();
            // Generating a slug for setTitle
            $program->setTitle('Série 2');
            // Setting the slug for Program object
            $program->setSlug($this->slugify->generate($program->getTitle()));
            $program->setSynopsis('Ca c\'est le synopsis de la série 2');
            $program->setPoster('https://www.serieslike.com/img/shop_01.png');
            $program->setCategory($this->getReference('category_1'));
            $program->addActor($this->getReference('actor_2'));
            $program->setOwner($this->getReference('admin'));
            $manager->persist($program);
            $this->addReference('program_2', $program);

        $manager->flush();
    }

    public function getDependencies()
    {
        // Return here all fixtures classes which ProgramFixtures depends on
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
            OUserFixtures::class,
        ];
    }
}
