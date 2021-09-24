<?php

namespace App\Controller;

use App\Form\UserProfileType;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(Request $request, UserPasswordHasherInterface $uphi): Response
    {
        // Mise en place du formulaire permettant la modification des informations de l'utilisateur dans la vue de profile 
        // 1 on récupère l'utilisateur connecté
        $user = $this->getUser();
        // dd($user);
        // On instancie un objet (pas le rendu) de formulaire d'après un modèle de
        // formulaire et on l'associe à l'utilisateur, du coup le formulaire
        // est associé aux données de l'utilisateur 
        $profileForm = $this->createForm(UserProfileType::class, $user);
        // On vérifie la possiblité d'hydrater (de remplir) le formulaire avec des données se trouvant dans la requête
        $profileForm->handleRequest($request);
        // Si on a pu hydrater le formulaire, on vérifie si il est envoyé et surtout valide
        if($profileForm->isSubmitted() && $profileForm->isValid()){
            $plainPassword = $profileForm->getData()->getPlainPassword();
            // dd($plainPassword);
            if(!is_null($plainPassword)){
                $encodedPassword = $uphi->hashPassword($user, $plainPassword);
                $user->setPassword($encodedPassword);
                $this->addFlash('warning', "Votre mot de passe a bien été changé.");
            }
            // On récupère un entité manager pour pouvoir gérer la mise en base de données
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            // On met en place un flashMessage
            $this->addFlash('success', "Vos informations ont bien été mises à jour.");
            // On redirige sur le route profile (oui c'est la même page) ce qui permet à Symfony de
            // supprimer les messages lorsqu'ils ont été affichés par le twig, sinon il reste en mémoire
            // ainsi que les informations du formulaire de l'utilisateur se trouvant dans la request
            // de sorte qui on recharge la page, les modifs sont continuellement refaites et les alert affichées
            return  $this->redirectToRoute("profile");
        }
        //
        return $this->render('profile/index.html.twig', [
            "form"=>$profileForm->createView(),// On passe à la vue le rendu du formulaire
        ]);
    }
    /**
     * @Route("/profile/addfavori")
     */
    public function addFavori(Request $request, LivreRepository $livreRepository):Response
    {
        // On récupère l'id du livre envoyé par Ajax
        $livreId = $request->request->get("id");
        // On récupère le livre
        $livre = $livreRepository->find($livreId);
        // On récupère le user connecté
        $user = $this->getUser();
        // On ajoute le livre dans la liste de l'utilisateur
        $user->addBookList($livre);
        // On récupère un entity manager pour faire un persist et un flush
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        // On retourne une réponse
        return new Response("ok");
    }
    /**
     * @Route("/profile/removefavori/{id}", name="deleteLivreListe")
     */
    public function removeFavori(int $id, LivreRepository $livreRepository):Response
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
