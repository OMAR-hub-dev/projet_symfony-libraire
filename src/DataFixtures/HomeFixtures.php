<?php

namespace App\DataFixtures;

use App\Entity\Home;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HomeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
            $home = new Home();
            $home->setTitre("bienvenue sur la librairie");
            $home->setTexte("Des nouveatés sont disponible pour cette rentrée 2021");
            $home->setActive(true);
            $manager->persist($home);

            $home = new Home();
            $home->setTitre("bienvenue sur la librairie");
            $home->setTexte("Apres les remises de prix, découvrez les livres de l'automne ");
            $home->setActive(false);
            $manager->persist($home);

        

        $manager->flush();
    }
}
