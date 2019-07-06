<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Service\PaginationService;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comment_index")
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination)
    {
        //$repo = $this->getDoctrine()->getRepository(Comment::class);
        //$comments = $repo->findAll();
        $pagination->setEntityClass(Comment::class)
            ->setLimit(5)
            ->setPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Allow To Edit The Comment
     * 
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     *
     * @param Comment $comment
     * @param Request $requet
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash(
                'success',
                "the Comment  No°: <strong>{$comment->getId()} </strong> has been edited successfully"
            );
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form'=>$form->createView()
        ]);
    }

    /**
     * Allow to delele comments
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param Request $request
     * @return Response
     */
    public function delele(Comment $comment, ObjectManager $manager)
    {

        $manager->remove($comment);
        $manager->flush();
        $this->addFlash(
            'success',
            "the comment  Of <strong>{$comment->getAuthor()->getFullName()}  </strong> has been delele successfully"
        );

        return $this->redirectToRoute('admin_comment_index');



    }
}
