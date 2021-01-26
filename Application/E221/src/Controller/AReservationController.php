<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\User;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
     * @Route("/reservation")
*/
class AReservationController extends AbstractController
{
    /**
     * @Route("/new/{bien}", name="reservation_save")
     */
    public function save(Bien $bien): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute("app_login");
        }

        $reservation = new Reservation();

        $reservation
        ->setClient($this->getUser())
        ->setBien($bien);

        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();

        return $this->redirectToRoute("reservation_show");

    }

    /**
     * @Route("/show/{client?}", name="reservation_show")
     * 
     */
    public function show(User $client=null, ReservationRepository $rep): Response
    {
        
        $reservations=[];
        if ($client) {
            $reservations = $rep->findBy(['client' => $client]);
        }else {
            $reservations = $rep->findAll();
        }

        return $this->render('reservation/show.html.twig', [
            'reservations' => $reservations,
            'etats'=> Reservation::ETAT,
        ]);
    }
}
