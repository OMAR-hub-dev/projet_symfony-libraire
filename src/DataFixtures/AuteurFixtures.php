<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AuteurFixtures extends Fixture
{
    // Mise en place de reference pour les auteurs afin de pouvoir les utiliser dans la fixture des livres.
    //Pour cela on cree des constantes que l'on va associer aux instances d'auteur crees.
    public const ODA = "ODA";
    

    public function load(ObjectManager $manager)
    {
        $date = new DateTimeImmutable();
        //
        $auteur = new Auteur();
        $auteur->setPrenom("ODA");
        $auteur->setNom("Eiichiro");
        $auteur->setUpdatedAt($date);
        $auteur->setImageName("robot.webp");
        $auteur->setBiographie("Dès l’âge de quatre ans, Oda décide de suivre une carrière de mangaka et se passionne pour les vikings (notamment grâce au manga Vic le Viking2 auquel il fait des allusions fréquentes dans One Piece[réf. nécessaire]) et les pirates.

        En 1992, il reçoit les honneurs du 44e Prix Tezuka pour sa nouvelle Wanted, un western dont le héros est hanté par le fantôme d’un homme qu’il a tué. L’année suivante voit sa première publication professionnelle, Un présent divin (神から未来のプレゼント, Kami kara mirai no puresento?), dans le Jump Original d’octobre 1993. La même année, il gagne le concours de talent mensuel organisé par la rédaction du Weekly Shōnen Jump, le prix du manga Tenkaichi, avec la nouvelle intitulée Le démon solitaire (一鬼夜行, Ikki Yakō?). ");


        $manager->persist($auteur);
        // On garde une reference de l'auteur . self:: accede aux constantes de la classe en statique
        $this->addReference(self::ODA, $auteur);

        
        $manager->flush();
    }
}
