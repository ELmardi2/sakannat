<?php
namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $adsRepo, UserRepository $uRepo){
        return $this->render(
            'home.html.twig', 
            ["title" => "Welcome to Sakannat",
                "ads"=>$adsRepo->findBestAds(3),
                "users"=>$uRepo->findBestUsers(2)
            ]
        );
    }

    /**
     * Route To About Us Page
     * 
     * @Route("/about", name="about_us")
     *
     * @return void
     */
    public function about(){
        return $this->render('about.html.twig', [
            "title" => "Welcome to about us Page"
        ]);
    }
}

 ?>