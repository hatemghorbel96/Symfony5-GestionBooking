<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
            $users = $em->createQuery('SELECT COUNT(u)FROM App\Entity\User u')->getSingleScalarResult();
            $ads = $em->createQuery('SELECT COUNT(a)FROM App\Entity\Ad a')->getSingleScalarResult();
            $bookings = $em->createQuery('SELECT COUNT(b)FROM App\Entity\Booking b')->getSingleScalarResult();
            $comments = $em->createQuery('SELECT COUNT(c)FROM App\Entity\Comment c')->getSingleScalarResult();

            $bestAds = $em->createQuery('SELECT AVG(c.rating) as note, a.titre,a.id,u.firstname,u.lastName,u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a 
            ORDER BY note DESC'
            )->setMaxResults(5)->getResult();

            $worstAds = $em->createQuery('SELECT AVG(c.rating) as note, a.titre,a.id,u.firstname,u.lastName,u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a 
            ORDER BY note ASC'
            )->setMaxResults(5)->getResult();
        return $this->render('admin/dashboard/index.html.twig', [
            'users' => $users,
            'ads' => $ads,
            'bookings' => $bookings,
            'comments' => $comments,
            'bestAds' =>$bestAds,
            'worstAds'=> $worstAds,
        ]);
    }
}
