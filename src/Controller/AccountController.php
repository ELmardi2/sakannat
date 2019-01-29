<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\UpdatePassword;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * allow to user to login
     * @Route("/login", name="account_login")
     * 
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            "hasError"  => $error !== null,
            "username" => $username
        ]);
    }

    /**
     * Allow to to user to logout
     * 
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout()
    {
        //rien
    }

    /**
     * allow user to register or create his account 
     * 
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                "success", "Your account has created successfully ! you can lonin now"
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('/account/registration.html.twig', [
            "form" => $form->createView()
        ]);
    }

   /**
    * allow to user edit his profile
    *
    * @Route("account/profile", name="account_profile")
    * @IsGranted("ROLE_USER")
    * 
    * @return Response
    */

    public function profile(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $manager->persist($user);
           $manager->flush();
           $this->addFlash(
            "success", "Your account's infos has saved successfully !"
        );
        }
        return $this->render('/account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Update password 
     *
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function passwordUpdate(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager){
        $passwordUpdate = new UpdatePassword();
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                
                $form->get('oldPassword')->addError(new FormError(
                    'The password you entred it is not your password actuel please check it')
                );
            }else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    "success", "Your password has set successfully"
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('/account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * to show my account directly
     * 
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function myAccount()
    {
        return $this->render('/user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
