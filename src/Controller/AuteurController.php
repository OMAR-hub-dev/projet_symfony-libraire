<?php

namespace App\Controller;

use App\Repository\AuteurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Container11uCYA3\PaginatorInterface_82dac15;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AuteurController extends AbstractController
{
    #[Route('/auteur', name: 'auteur')]
    /**
     * la method index recout une injection de dépendance qui correspond à la repository des livres puisque cette methode est censée lister tous les livres
     *
     * @param AuteurRepository $auteurRepository
     * @return Response
     */
    public function index(AuteurRepository $auteurRepository, PaginatorInterface $paginator, Request $request ): Response
    {
        //la method render attend 2 params : la vue a renvoyer et dans un tableau asoociatif, la ou la variables utilisables par le twig et leur valeurs
        //Comme on veut paginer la liste on ne peut passer un twig la requete findAll()
        //tout d'abord on fait une requete
        $auteurs = $auteurRepository->findAll();
        //on fait appel au paginator pour paginer (deviser) le nombre d'entrées retournées par la requete suivant le nombre a afficher par page
        $pagination = $paginator->paginate(
            $auteurs, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
            );
        //
        return $this->render('auteur/index.html.twig', [
            //'auteurs'=>$auteurRepository->findAll(),
            'auteurs'=>$pagination,

        ]);
    }
    /**la route déclarée est une route contenant une partie dynamique (slug) d'ou l'utilisation des {} dans la déclaration
     * il faut penser àa donner un nom à la route
     * pour connaitre les routes déja existante dans le terminal "php bin/console debug:router"
     * 
     */
    #[Route('/auteur-detail/{id}', name: 'auteur-detail')]
    public function detail(AuteurRepository $auteurRepository,int  $id  ): Response
    {
        
        return $this->render('auteur/detail.html.twig',[
            "auteur"=>$auteurRepository->find($id),
        ]);
        
    }
}
