<?php

namespace App\Controller;

use App\Repository\HomeRepository;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(HomeRepository $homeRepository, LivreRepository $livreRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'homeContent' => $homeRepository->findOneBy(["active"=>true]) ,
            'livres'=> $livreRepository->findBy([],["updatedAt"=>"DESC"], 4)
        ]);
    }
}
