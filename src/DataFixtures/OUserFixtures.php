<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OUserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) 
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d’un utilisateur de type “contributeur” (= auteur)
            $contributor = new User();
            $contributor->setEmail('contributor@monsite.com');
            $contributor->setRoles(['ROLE_CONTRIBUTOR']);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $contributor,
                'password'
            );
            $contributor->setPassword($hashedPassword);
            $manager->persist($contributor);
            $this->addReference('contributor', $contributor);
        
        // Création d’un utilisateur de type “administrateur”
            $admin = new User();
            $admin->setEmail('admin@monsite.com');
            $admin->setRoles(['ROLE_ADMIN']);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $admin,
                'password'
            );
            $admin->setPassword($hashedPassword);
            $manager->persist($admin);
            $this->addReference('admin', $contributor);
        

        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
