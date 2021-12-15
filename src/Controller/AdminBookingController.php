<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    #[Route('/admin/booking', name: 'admin_booking')]
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }


    #[Route('/admin/booking/{id}/edit', name: 'admin_edit_booking')]
    public function edit(Booking $booking,Request $request, EntityManagerInterface $em): Response
    {
        $form=$this->createForm(AdminBookingType::class,$booking,[
            'validation_groups'=>["front"]
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $booking->setMontant(0);
            $em->persist($booking);
            $em->flush();
            $this->addFlash('success',
            "la réservation n° {$booking->getId()} a bien éte modifiée"
            
        );
            return $this->redirectToRoute('admin_booking');
        } 
        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form'=>$form->createView()
        ]);
    }

    #[Route('/admin/booking/{id}/delete', name: 'admin_supp_booking')]
    public function delete(Booking $booking,EntityManagerInterface $em): Response
    {        
        
        $em->remove($booking);
        $em->flush();
        $this->addFlash('success',
        "la reservation a bien été supprimer"       
    );
        return $this->redirectToRoute ('admin_booking');        
    }

}
