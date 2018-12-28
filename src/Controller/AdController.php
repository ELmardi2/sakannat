<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Ad::class);

        $ads = $repo->findAll();
        
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * Add new announces
     * 
     * @Route("/ads/new", name="ad_create")
     * 
     * 
     * @return Response
     * 
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image) {

                $image->setAd($ad);
                
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                " Your announce <strong>{$ad->getTitle()}</strong> has been added successfully"
            );

            return $this->redirectToRoute("ads_show", [
                "slug" => $ad->getSlug(),
                
            ]);
        }

        return $this->render('ad/new.html.twig',[
            "form" =>$form->createView()
        ]);
    }

        /**
     * Edition of the announce
     * @Route("/ads/{slug}/edit", name="ad_edit")
     * 
     * 
     * @return Response
     */

    public function edit(Ad $ad, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image) {

                $image->setAd($ad);
                
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                " Your announce <strong>{$ad->getTitle()}</strong> has been modified  successfully"
            );

            return $this->redirectToRoute("ads_show", [
                "slug" => $ad->getSlug(),
                
            ]);
        }

        return $this->render('/ad/edit.html.twig', [
            "form" => $form->createView(),
            "ad" => $ad
        ]);
    }

    /**
     * show the announce's details
     * 
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response
     * 
     */
    
     public function show(Ad $ad)
     {
         return $this->render('/ad/show.html.twig', [
             "ad" => $ad
         ]);
     }


}
