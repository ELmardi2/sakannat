<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends Controller
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, ObjectManager $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking,[
            'validation_groups' => ["default",
             "front"]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
         {

                $user = $this->getUser();

                $booking->setBooker($user)
                    ->setAd($ad);

                    //if dates selected does not available
                    if (!$booking->isBookableDates()) {
                        $this->addFlash(
                            'warning',
                            "The Date You Selected Are not Availabels please change them Thanks!"
                        );
                    }else {
                        //Otherwise save and redirectToRoute....
                        $manager->persist($booking);
                        $manager->flush();
            
                        return $this->redirectToRoute('booking_show', ['id'=>$booking->getId(), 
                        "withAlert" => true]);
                }

            }

        

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Allow to show reservation page
     * @Route("/booking/{id}", name="booking_show")
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $Manager
     * @return Response
     */
    public function show(Booking $booking, ObjectManager $manager, Request $request)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setad($booking->getad())
                ->setAuthor($this->getuser());
                $manager->persist($comment);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "Thanks Your Comment Has Been Token in account"
                );
        }

        return $this->render('/booking/show.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
