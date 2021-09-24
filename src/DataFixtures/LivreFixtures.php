<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $date = new DateTimeImmutable();
        //
        $livre = new Livre();
        $livre->setTitre("OnePiece");
        $livre->setDescription("L'histoire suit les aventures de Monkey D. Luffy, un garçon dont le corps a acquis les propriétés du caoutchouc après avoir mangé par inadvertance un fruit du démon. Avec son équipage de pirates, appelé l'équipage de Chapeau de paille, Luffy explore Grand Line à la recherche du trésor ultime connu sous le nom de « One Piece » afin de devenir le prochain roi des pirates. ");
        $livre->setUpdatedAt($date);
        $livre->setImageName("beach-6514331-340-612cb11f74b14275249633.webp");
        // Comme on a des references aux auteurs dans AuteurFixtures, on recupere ces references (objet de type Auteur) afin de les declarer dans la propriete auteur d'un livre.
        $livre->setAuteur($this->getReference(AuteurFixtures::ODA));


        $manager->persist($livre);

        $manager->flush();
    }
}
