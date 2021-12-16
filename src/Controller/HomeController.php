<?php


namespace App\Controller;


use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/",name="homepage")
     */
    public function home(AdRepository $adRepository ,UserRepository $userRepository){
      $best=$adRepository->findbestAds();
        return $this->render(
          'home.html.twig'
        ,['bestAds'=>$best,
          'bestUser'=>$userRepository->findBestUsers(),'ads'=>$adRepository->findAll()]
      );

    }

}