<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\User;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/{id?}", name="reservation_index", methods={"GET"})
     */
    public function index(User $client=null,ReservationRepository $reservationRepository): Response
    {

        if ($client) {
            return $this->render('e221/pagnet.html.twig', [
                'reservations' => $reservationRepository->findBy(['client' => $client]),
                'info'=>""

            ]);
        }

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    // /**
    //  * @Route("/new", name="reservation_new", methods={"GET","POST"})
    //  */
    // public function new(Request $request): Response
    // {
    //     $reservation = new Reservation();
    //     $form = $this->createForm(ReservationType::class, $reservation);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($reservation);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('reservation_index');
    //     }

    //     return $this->render('reservation/new.html.twig', [
    //         'reservation' => $reservation,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="reservation_show", methods={"GET"})
    //  */
    // public function show(Reservation $reservation): Response
    // {
    //     return $this->render('reservation/show.html.twig', [
    //         'reservation' => $reservation,
    //     ]);
    // }

    // /**
    //  * @Route("/{id}/edit", name="reservation_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, Reservation $reservation): Response
    // {
    //     $form = $this->createForm(ReservationType::class, $reservation);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('reservation_index');
    //     }

    //     return $this->render('reservation/edit.html.twig', [
    //         'reservation' => $reservation,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="reservation_delete", methods={"DELETE"})
    //  */
    // public function delete(Request $request, Reservation $reservation): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($reservation);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('reservation_index');
    // }

    /**
     * @Route("/{id}/reserver", name="reservation_reserver")
     */
    public function reserver(Bien $bien, ReservationRepository $reservationRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute("app_login");
        }
        //Si bien déja réservé
        if ($reservationRepository->findBy(["bien"=>$bien,"client"=>$this->getUser()])){
            return $this->render('e221/pagnet.html.twig', [
                'reservations'=> $reservationRepository->findBy(['client'=>$this->getUser()]),
                'info'=>"Ce bien exit déja dans vos réservation"
            ]);
        }

        $reservation = new Reservation();
        $reservation
        ->setBien($bien)
        ->setClient($this->getUser());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->render('e221/pagnet.html.twig', [
            'reservations'=> $reservationRepository->findBy(['client'=>$this->getUser()]),
            'info'=>""
        ]);
    }

    /**
     * @Route("/{id}/valider", name="reservation_valider")
     */
    public function valider(Reservation $reservation): Response
    {
        $reservation->setEtat(Reservation::ETAT[1])->getBien()->setEtat(Bien::ETAT["Loué"]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->redirectToRoute("reservation_index");
    }

    /**
     * @Route("/{id}/annuler", name="reservation_annuler")
     */
    public function annuler(Reservation $reservation): Response
    {
        $reservation->setEtat(Reservation::ETAT[0]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->redirectToRoute("reservation_index");

    }
}
