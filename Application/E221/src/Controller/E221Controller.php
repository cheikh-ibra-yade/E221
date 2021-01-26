<?php

namespace App\Controller;

use App\Repository\BienRepository;
use App\Repository\ZoneRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
     * @Route("/")
*/
class E221Controller extends AbstractController
{
    /**
     * @Route("Accueil", name="E221_Accueil")
     */
    public function index(BienRepository $rep): Response
    {
        $biens = $rep->findBy(['etat'=>"Libre"],null,9);
        return $this->render('e221/index.html.twig', [
            'biens' => $biens,
        ]);
    }

    /**
     * @Route("Catalogue", name="E221_Catalogue")
     */
    public function catelogue(BienRepository $rep, ZoneRepository $repZon): Response
    {
        $biens = $rep->findBy(['etat' => "Libre"]);
        return $this->render('e221/catalogue.html.twig', [
            'biens' => $biens,
            'zones'=>$repZon->findAll()
        ]);
    }

    //Catalogue pagination
    // /**
    //  * @Route("Catalogue", name="E221_Catalogue")
    //  */
    // public function listAction(BienRepository $rep,ZoneRepository $repZon, PaginatorInterface $paginator, Request $request)
    // {
    // $query = $rep->findBienEtat("Libre");

    // $pagination = $paginator->paginate(
    //     $query, /* query NOT result */
    //     $request->query->getInt('page', 1), /*page number*/
    //     10 /*limit per page*/
    // );

    // // parameters to template
    // return $this->render('e221/catalogue.html.twig', [
    //     'pagination' => $pagination,
    //     'zones'=>$repZon->findAll()
    //     ]);
    // }

    
}
