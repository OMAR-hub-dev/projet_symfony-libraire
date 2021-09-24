<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMessageController extends AbstractController
{
    #[Route('/admin/message', name: 'admin_message')]
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('admin_message/index.html.twig', [
            'messages' =>$contactRepository->findAll(),
        ]);
    }

    #[Route('/admin/message/delete/{id}', name: 'delete-message')]
    public function deletMessage(int $id, ContactRepository $contactRepository): Response
    {
        //on recupere un entity manager
        $em = $this->getDoctrine()->getManager();
        $contact = $contactRepository->find($id);
        //on supprime le conatact et met à jour la bdd
        $em->remove($contact);
        $em->flush();
        //on rederige
        return $this->redirectToRoute("admin_message");

    }

}
