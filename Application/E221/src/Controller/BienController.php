<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\User;
use App\Entity\Zone;
use App\Form\BienType;
use App\Repository\BienRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/bien")
 */
class BienController extends AbstractController
{
    /**
     * @Route("/show/{id?}", name="bien_index", methods={"GET"})
     */
    public function index(User $user=null,BienRepository $bienRepository): Response
    {
        $biens=[];
        if ($user) {
            $biens = $bienRepository->findBy(['user' => $user]);
        }else {
            $biens = $bienRepository->findAll();
        }

        return $this->render('bien/index.html.twig', [
            'biens' => $biens,
        ]);
    }

    /**
     * @Route("/new", name="bien_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bien = new Bien();
        $bien->setUser($this->getUser());

        $form = $this->createForm(BienType::class, $bien);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //check if new zone
            if (!$form->get("zone")->getData()) {
                //Creation de la zone
                $zone = new Zone();
                $zone->setLibelle($form->get("zoneField")->getData());
                //insertion et recuperation
                $zone = $em->merge($zone);
                $em->flush();
                $bien->setZone($zone);
            }
            $em->persist($bien);
            $em->flush();
            return $this->redirectToRoute('bien_index',['id'=>$this->getUser()->getId()]);
        }
        return $this->render('bien/new.html.twig', [
            'bien' => $bien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/detail", name="bien_show", methods={"GET"})
     */
    public function show(Bien $bien): Response
    {
        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bien_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bien $bien): Response
    {
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bien_index');
        }

        return $this->render('bien/edit.html.twig', [
            'bien' => $bien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bien_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bien $bien): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bien->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bien);
            $entityManager->flush();
        }
        if ($this->getUser()->getProfile()->getLibelle()=="Gestionnaire") {
            # code...
            return $this->redirectToRoute('bien_index');
        }else {
            return $this->redirectToRoute('bien_index',['id'=>$this->getUser()->getId()]);
            # code...
        }
    }

    /**
     * @Route("/{id}/accepter", name="bien_accepter")
     * 
     */
    public function accepte(Bien $bien): Response
    {

        if ($bien->getEtat()=="Encours") {
            $mntTotal = $bien->getMontant() * 1.05;
            $bien->setEtat(Bien::ETAT["Libre"])->setMontant($mntTotal);
            $em = $this->getDoctrine()->getManager();
            $em->persist($bien);
            $em->flush();($bien);
        }
        
        if ($this->getUser()->getProfile()->getLibelle()=="Gestionnaire") {
            # code...
            return $this->redirectToRoute('bien_index');
        }else {
            return $this->redirectToRoute('bien_index',['id'=>$this->getUser()->getId()]);
            # code...
        }

    }

    /**
     * @Route("/{id}/refuser", name="bien_refuser")
     * 
     */
    public function bloquer(Bien $bien): Response
    {
        $bien->setEtat(Bien::ETAT["Bloqué"]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($bien);
        $em->flush();($bien);
        
        if ($this->getUser()->getProfile()->getLibelle()=="Gestionnaire") {
            # code...
            return $this->redirectToRoute('bien_index');
        }else {
            return $this->redirectToRoute('bien_index',['id'=>$this->getUser()->getId()]);
            # code...
        }

    }
}
