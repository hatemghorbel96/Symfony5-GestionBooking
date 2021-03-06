<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    #[Route('/ads/{slug}/book', name: 'booking_create')]
    public function create(Ad $ad,Request $request,EntityManagerInterface $manager): Response
    {
        $booking =new Booking();
        $form= $this->createForm(BookingType::class,$booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ){

            $user =$this->getUser();
            $booking->setBooker($user)->setAd($ad);

            // si les dates ne sont pas disponibles ,message d'erreur
            if(!$booking->isBookableDates()){
                $this->addFlash(
                    'warning',"les dates que vous avez choisi ne peuvent etre réservées : elles sont déja prises."
                );
            }else{
                $manager->persist($booking);
                $manager->flush(); 
                return $this->redirectToRoute('booking_show',['id'=>$booking->getId(),
             'withAlert'=>true]);
            }
       

        }
        return $this->render('booking/index.html.twig', [
            'ad'=>$ad,
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/booking/{id}",name="booking_show")
     */

     public function show (Booking $booking,Request $request,EntityManagerInterface $manager): Response{

        $comment=new Comment();
        $form=$this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()  ) {
               
                 $comment->setAd($booking->getAd());
                 $comment->setUser($this->getUser());
                 $comment->setCreatedAt(new \DateTimeImmutable('now'));
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($comment);
             $entityManager->flush();
             $this->addFlash(
                'success',
                "Votre commentaire a bien été pris en compte !");
                return $this->redirectToRoute('booking_show', ['id' => $booking->getId()]);
                
             }
        return $this->render('booking/show.html.twig',[
           'booking'=>$booking,
           'form'=>$form->createView()
        ]);
     }
}
