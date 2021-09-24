<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    //on declare une propriété (privé parce qu'elle ne concerne que la fixture) qui va nous permettre d'accéder au userPasswordHasherInterface partout dans la method load(méthode native dans laquelle on ne pas faire l'injection)
    private $encoder;
    /**
     * on met en place un constructeur afin de pouvoir injecter le UserPasswordHasherInterface dand la class et pouvoir l'utiliser notament dans la methode load(method native dans laquelle on ne peut pas faire l'injection)
     *
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        // on mémorise le userPasswordHasherInterface injecte dans la proprioté de class de sorte qu'on y aura accés depuis toutes les methodes de la class 
        $this->encoder = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager)
    {
            //Role Admin
            $user = new User();
            // on renseigne la propriorité email à l'aide du setter
            $user->setEmail('admin@admin.com');
            //gestion de mot de pass
            $plainPassword ="pass";//le mot de pass en clair quel'on veu coder
            $encodedPassword= $this->encoder->hashPassword($user, $plainPassword);// on encode le password avec l'encoder mémorisé dans le constructeur
            $user->setPassword($encodedPassword);// on reseigne la propriété password de l'utilisateur avec le setter 
            $user->setRoles(["ROLE_USER","ROLE_ADMIN"]);
            $user->setIsVerified(1);// on met la propriete isVerified à 1 pour les utilisateurs aient le droit de se connecter 
            // on mémorise l'instance de l'utilisteura fin d'ajouter ultérieurement dans la base de données
            $manager->persist($user);
            
            //simple User************ 
            $user = new User();
            // on renseigne la propriorité email à l'aide du setter
            $user->setEmail('user@admin.com');
            //gestion de mot de pass
            $plainPassword ="pass";//le mot de pass en clair quel'on veu coder
            $encodedPassword= $this->encoder->hashPassword($user, $plainPassword);// on encode le password avec l'encoder mémorisé dans le constructeur
            $user->setPassword($encodedPassword);// on reseigne la propriété password de l'utilisateur avec le setter 
            $user->setRoles(["ROLE_USER"]);
            $user->setIsVerified(1);
            // on mémorise l'instance de l'utilisteura fin d'ajouter ultérieurement dans la base de données
            $manager->persist($user);
            
        // on met en  BDD
        $manager->flush();
    }
}
