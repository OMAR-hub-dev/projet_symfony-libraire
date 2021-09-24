<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreController extends AbstractController
{
    #[Route('/livre', name: 'livre')]
    /**
     * la method index recout une injection de dépendance qui correspond à la repository des livres puisque cette methode est censée lister tous les livres
     *
     * @param LivreRepository $livreRepository
     * @return Response
     */
    public function index(LivreRepository $livreRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $livres = $livreRepository->findAll();
        $pagination = $paginator->paginate(
            $livres, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
            );
        //la method render attend 2 params : la vue a renvoyer et dans un tableau asoociatif, la ou la variables utilisables par le twig et leur valeurs
        return $this->render('livre/index.html.twig', [
            //'livres'=>$livreRepository->findAll(),
            'livres'=>$pagination,
        ]);
    }
    
    /**
     * 
     */
    #[Route('/livre/search', name:'search_book', methods:['GET'])]
    public function search(LivreRepository $livreRepository,PaginatorInterface $paginator, Request $request): Response 
    {
        $value = $request->query->get("search-value");// on cherche dans la requete(barre d'adress) une variable nomée search-value issue d'un name d'input de formulaire
        //dd($value);// dd dump and die
        //
        //$livres= $livreRepository->search($value);
        $livres= $livreRepository->searchForPaginator($value);
        
        $pagination = $paginator->paginate(
            $livres, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            1 /*limit per page*/
            );
        return $this->render('livre/index.html.twig', ['livres'=>$pagination]);

    }

    /**la route déclarée est une route contenant une partie dynamique (slug) d'ou l'utilisation des {} dans la déclaration
     * il faut penser àa donner un nom à la route
     * pour connaitre les routes déja existante dans le terminal "php bin/console debug:router"
     * 
     */
    #[Route('/livre/{slug}', name: 'livre-detail')]
    public function detail(LivreRepository $livreRepository, int $slug ): Response

    {
        return $this->render('livre/detail.html.twig',[
            "livre"=>$livreRepository->find($slug),
        ]);
        
    }
}
