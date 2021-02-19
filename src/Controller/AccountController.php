<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(): Response
    {
        return $this->render('account/login.html.twig');
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout(){

    }

    /**
     * @Route ("/register", name="account_register")
     * @return Response
     */
    public function register(Request $request,EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder){
        $user=new User();
        $form =$this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash=$encoder->encodePassword($user,$user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "votre compte a bien ete cree"
            );
            return $this->redirectToRoute("account_login");
        }

        return $this->render('account/registration.html.twig',[
        'form'=>$form->createView()
]);
    }

    /**
     * @Route("/account/profile", name="account_profile")
     * @return Response
     */
    public function profile(Request $request,EntityManagerInterface $manager){
        $user =$this->getUser();

        $form =$this->createForm(AccountType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "les données du profil ont éte enregister avec succés"
            );
        }
        return $this->render('account/profile.html.twig',[
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/account/password-update",name="account_password")
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager){
/**@var User $user */
        $user = $this->getUser();
        $passwordUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateType::class,$passwordUpdate);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            if(!password_verify($passwordUpdate->getOldPassword(),$user->getHash())) {
                $form->get('oldPassword')->addError(new FormError("le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            }else{
                $newPassword =$passwordUpdate->getNewPassword();

                $hash = $encoder->encodePassword($user,$newPassword);
                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();
            $this->addFlash(
                'success',
                "votre mot de passe a bien ete modifier !"
            );
                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('account/password.html.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route ("/account", name="account_index")
     * @return Response
     */
    public function myAccount(){
        return $this->render('user/index.html.twig',[
            'user'=>$this->getUser()
        ]);

    }
}
