<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/user')]
class AdminUserController extends AbstractController
{
    
    #[Route('/new/{role}', name: 'admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request,string $role, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $user = new User();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);//on hydrate le formulaire, donc le user avec les donées contenues dans la requete, ca fonctionne que si on est

        if ($form->isSubmitted() && $form->isValid()) {
            // prise en chatge du role
            ($role=="user")? $user-setRoles(["ROLE_USER"]): $user->setRoles(["ROLE_USER","ROLE_ADMIN"]);
            // prise en chatge du password
            $user->setPassword($userPasswordHasherInterface->hashPassword($user, $user->getPlainPassword()));
            //activation de compte
            $user->setIsVerified(true);
            //Memoriser et mise en BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        
            //on passe le role dans le tableau prevyu à cette effet lorsqu'on redirige vers la liste
            return $this->redirectToRoute('admin_user_index', ["role"=>$role], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'role' => $role,
            
        ]);
    }

    #[Route('/{id}', name: 'admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        //Comme on a une route dynamique sur les roles pour les listes d'utilisateur
        // On analyse le role du $user et suivant sont role on crée une variable $role
        // qui sera passée au twig pour faire un lien dynamique dans le vue show pour le bouton
        // de retour à la liste

        if(in_array("ROLE_ADMIN", $user->getRoles())){
            $role="admin";
        }else{
            $role="user";
        }
        return $this->render('admin_user/show.html.twig', [
            'user' => $user,
            'role'=>$role
        ]);
    }


    #[Route('/liste/{role}', name: 'admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, string $role): Response
    {
        return $this->render('admin_user/index.html.twig', [
            'users' => $userRepository->getByRole($role),
            'title' => ($role =="user")? 'utilisateurs' : 'administrateurs',
            'role'  =>$role,
        ]);
    }

    
    

    #[Route('/{id}/edit/{role}', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, $role): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userForData = $request->request->get('user'); //on recupere les données du formulaire posté dans un tableau
            $roleToCheck =$userForData["role"];// on recupere la vlaeur de ChoiceType de la propriété $role dasn un formulaire
            if ($roleToCheck==="ROLE_ADMIN") {
                $user->setRoles(["ROLE_USER","ROLE_ADMIN"]);
            } else {
                $user->setRoles(["ROLE_USER"]);
            }
            // on fluch pour mettre à jour dans la bdd
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_user_index', ["role"=>$role], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'title' => ($role =="user")? 'utilisateur' : 'administrateur',
            'role'  =>$role
        ]);
    }

    #[Route('/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
