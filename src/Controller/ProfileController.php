<?php

namespace App\Controller;

use App\Form\UserProfileType;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(Request $request, UserPasswordHasherInterface $uphi): Response
    {
        //Mise ne place d'un formulaire permettant la modification des informations de l'utilisateur dans la vue de profil
        
        $user = $this->getUser();//1-on recupere le user connecté dans une variable
        $profileForm=$this->createForm(UserProfileType::class, $user);//2- on instancie un objet (pas le rendu) de formulaire d'apres un model de formulaire et l'on associe à l'utilistaeur, du coup le formulaire est associé aux données de l'user
        $profileForm->handleRequest($request);//on hydrate le formulaire, on verifie s'il est envoyé et surtt validé
        if($profileForm->isSubmitted() && $profileForm->isValid())
        {
            $plainPassword= $profileForm->getData("plainPassword")->getPlainPassword();
            if (! is_null($plainPassword))
            {
                $encodedPassword = $uphi->hashPassword($user, $plainPassword);
                $user->setPassword($encodedPassword);
                $this->addflash('warning', "Votre mot de passe a bien ete changé. ");
            }
            
            //on recupere un entité manager pour pouvoir gerer la mise en bdd
            $em= $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            //On met en place un flashMessage
            $this->addflash('success', "Vos information ont bien ete mis a jour. ");
            // On redirige sur la route profile (oui c'est la meme page) ce qui permet à Symfony
            //de supprimer les messages lorsqu'ils ont été affichés par le twig, sinon il reste en memoire
            // ainsi que les informations du formulaire de l'utilisateur se trouvant dans la request de sorte que on recharge la page, les modif sont continuellment refaites et les alert affichées
            return $this->redirectToRoute("profile");
        }
        //
        return $this->render('profile/index.html.twig', [
            "form"=>$profileForm->createView(),//3- on passe a la vue le rendu du formulaire 
        ]);
    }

    #[Route('/profile/addfavori')]
    public function addFavori (Request $request, LivreRepository $livreRepository): Response
    {
        //on recupere Id du livre par ajax
        $livreId = $request->request->get("id");
        //on recupere User connecté
        $livre= $livreRepository->find($livreId);
        //on ajoute le livre dans la liste de l'utilisateur
        $user=$this->getUser();

        $user->addBookList($livre);
        //on recupere un entity manager pour faire un persiste et un flush
        $em =$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        //on retourne une reponse
        return new Response ( "ok");
    }
    #[Route('/profile/removefavori/{id}', name:'deleteLivreListe')]
    public function removeFavori(int $id, LivreRepository $livreRepository): Response
    {
       
        // On récupère le livre
        $livre = $livreRepository->find($id);
        // On récupère le user connecté
        $user = $this->getUser();
        // On ajoute le livre dans la liste de l'utilisateur
        $user->removeBookList($livre);
        // On récupère un entity manager pour faire un persist et un flush
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        // Redirection
        return $this->redirectToRoute("profile");
    }

}
