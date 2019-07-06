<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination)
    {
        /**
         * find method to find ad by Id ex
         */
        // $limit = 7;
        // $start = $page * $limit - $limit;
        // //get the of the pages
        // $total = count($repo->findAll());
        // $pages = ceil($total / 8);
        $pagination->setEntityClass(Ad::class)
            ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
            // 'ads' => $repo->findBy([], [], $limit, $start),
            // 'pages' => $pages,
            // 'page' => $page
            'pagination' => $pagination
        ]);
    }

    /**
     * Edit annouces form
     * 
     * @Route("admin/ad/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "the annouce <strong>{$ad->getTitle()} </strong> has been edited successfully"
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form'=>$form->createView()
        ]);
    }

    /**
     * Allow to delete annouce
     * 
     * @Route("/admin/ads/{id}/delele", name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager)
    {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "you can't delete the annouce <strong>{$ad->getTitle()}</strong> it has already reservation !"
            );
        }else 
        {
            $manager->remove($ad);

            $manager->flush();

         $this->addFlash(
            'success',
            "the annouce <strong>{$ad->getTitle()}</strong> has been deleted successfully"
            );
        }

        return $this->redirectToRoute('admin_ads_index');
    }
}
