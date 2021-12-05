<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    public const CATEGORIES = [
        'Action',
        'Adventure',
        'Animation',
        'Fantastique',
        'Horreur',
        'Dramatique',
        'Biopic',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $key => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            // Adding a reference to each created object
            $this->addReference('category_' . $key, $category);
        }
        $manager->flush();
    }
}