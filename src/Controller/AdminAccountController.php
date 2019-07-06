<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
   /**
    * Access as admin
    * 
    * @Route("/admin/login", name="admin_account_login")
    *
    * @param AuthenticationUtils $utils
    * @return void
    */
    public function login(AuthenticationUtils $utils)
    {
        //
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('/admin/account/login.html.twig', [
             "hasError"  => $error !== null,
            "username" => $username,
        ]);
    }

    /**
     * To logout only
     *
     * @Route("/admin/logout", name="admin_account_logout")
     * 
     * @return void
     * 
     */
    public function logout()
    {
        //........
    }
}
