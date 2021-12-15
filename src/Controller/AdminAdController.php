<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use App\Form\AdType;
use App\Form\EditUserType;
use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminAdController extends AbstractController
{


    #[Route('/homeadmin', name: 'admin_home')]
    public function home(AdRepository $adRepository): Response
    {
        return $this->render('admin/account/home.html.twig', [
            'ads' => $adRepository->findAll(),
        ]);
    }

    #[Route('/ads', name: 'admin_ads')]
    public function index(AdRepository $adRepository): Response
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $adRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_edit_ads')]
    public function edit(Ad $ad,Request $request, EntityManagerInterface $em): Response
    {
        $form=$this->createForm(AdType::class,$ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($ad);
            $em->flush();
            $this->addFlash('success',
            "l'annonce <strong>{$ad->getTitre()}</strong> a bien été modifier"
            
        );

        } 
        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form'=>$form->createView()
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_supp_ads')]
    public function delete(Ad $ad,EntityManagerInterface $em): Response
    {        
        if(count($ad->getBookings())>0 ){
            $this->addFlash('warning',
            "Vous ne pouvez pas supprimer l'annonce {$ad->getTitre()} car elle posséde déja des réservations !"          
        );
        } else {
        $em->remove($ad);
        $em->flush();
        $this->addFlash('success',
        "l'annonce {$ad->getTitre()} a bien été supprimer"       
    );}
        return $this->redirectToRoute   ('admin_ads');        
    }

 

/**
* @Route("/utilisateurs", name="admin_utilisateurs")
*/
    public function usersList(UserRepository $user) {
    return $this->render("admin/account/users.html.twig",[
    'users' => $user->findAll()
    ]);
    }


         /**
        * @Route("/utilisateurs/modifier/{id}", name="admin_modifier_utilisateur")
        */
        public function editUser(Request $request, User $user, EntityManagerInterface $em) {
        $form = $this->createForm(EditUserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('admin_utilisateurs');
        }
        return $this->render('admin/account/editUser.html.twig', ['formUser' => $form->createView()]);
        }

}
