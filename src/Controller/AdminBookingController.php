<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     */
    public function index(BookingRepository $repo,$page, PaginationService $pagination)
    {
        //$repo = $this->getDoctrine()->getRepository(Booking::class);
        //$bookings = $repo->findAll();
        $pagination->setEntityClass(Booking::class)
            ->setPage($page);

        // $bookings = $pagination->getData();
        //  //get the of the pages
        //  $total = count($repo->findAll());
        //  $pages = ceil($total / 8);

        return $this->render('admin/booking/index.html.twig', [
            // 'bookings' => $pagination->getData(),
            // 'pages' => $pagination->getPages(),
            // 'page' => $page
            'pagination' => $pagination,
        ]);
    }

    /**
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     * 
     * @return Response
     * 
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager )
    {
      
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();
            $this->addFlash(
                'success',
                "the Comment  No°: <strong>{$booking->getId()} </strong> has been edited successfully"
            );
            $this->redirectToRoute('admin_booking_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * Allow to delele bookings
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     *
     * @param Booking $booking
     *
     * @return Response
     */
    public function delele(Booking $booking, ObjectManager $manager)
    {

        $manager->remove($booking);
        $manager->flush();
        
        $this->addFlash(
            'success',
            "the booking has been delele successfully"
        );

        return $this->redirectToRoute('admin_booking_index');

    }
}
