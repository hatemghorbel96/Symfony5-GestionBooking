<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/admin')]
class AdminCommentController extends AbstractController
{
    #[Route('/comment', name: 'admin_comment')]
    public function index(CommentRepository $comRep): Response
    {
       
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comRep->findAll(),
        ]);
    }

    #[Route('/comment/{id}/edit', name: 'admin_edit_comment')]
    public function edit(Comment $comment,Request $request, EntityManagerInterface $em): Response
    {
        $form=$this->createForm(AdminCommentType::class,$comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success',
            "le commentaire de <strong>{$comment->getUser()->getfullName()}</strong> a bien été modifier"
            
        );
    }       
        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form'=>$form->createView()          
        ]);
    }

    #[Route('comment/{id}/delete', name: 'admin_supp_comment')]
    public function delete(Comment $comment,EntityManagerInterface $em): Response
    {        
        
        $em->remove($comment);
        $em->flush();
        $this->addFlash('success',
        "le commentaire de{$comment->getuser()->getfullname()} a bien été supprimer"       
    );
        return $this->redirectToRoute ('admin_comment');        
    }

}
