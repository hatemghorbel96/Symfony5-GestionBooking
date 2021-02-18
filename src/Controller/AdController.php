<?php

namespace App\Controller;

use App\Entity\Ad;

use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo): Response
    {


        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [

            'ads' => $ads,
        ]);
    }

    /**
     * @Route ("/ads/new", name="ads_create")
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager){
        $ad = new Ad();
        $image = new Image();

        $image->setUrl('http://placehold.it/400x200')
                ->setCaption('Titre 1');
        $ad->addImage($image);

        $image2 = new Image();

        $image2->setUrl('http://placehold.it/400x200')
            ->setCaption('Titre 1');
        $ad->addImage($image2);

        $form = $this->createForm(AdType::class,$ad);

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            //$manager = $this->getDoctrine()->getManager();
foreach ($ad->getImages() as $image){
    $image->setAd($ad);
    $manager->persist($image);
}
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce {$ad->getTitre()} a bien été enregistrée !"
            );
            return $this->redirectToRoute('ads_show',[
                'slug'=>$ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig',
        ['form'=>$form->createView()]);

    }

    /**
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @return Response
     */
    public function edit(Ad $ad,Request $request,EntityManagerInterface $manager){

        $form=$this->createForm(AdType::class,$ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //$manager = $this->getDoctrine()->getManager();
            foreach ($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "les modification de L'annonce {$ad->getTitre()} a bien été enregistrée !"
            );
            return $this->redirectToRoute('ads_show',[
                'slug'=>$ad->getSlug()
            ]);
        }
        return $this->render('ad/edit.html.twig',[
            'form'=>$form->createView(),
            'ad'=>$ad
        ]);

    }

    /**
     * @Route ("/ads/{slug}",name="ads_show")
     */
    public function show(Ad $ad){
     //$ad =$repo->findOneBySlug($slug);

     return $this->render('ad/show.html.twig',[
        'ad'=>$ad
     ]);
    }


}
