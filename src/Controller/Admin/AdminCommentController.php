<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminCommentController extends AbstractController
{
    /**
     * @var CommentRepository
     */
    protected $repository;
    /**
     * @var EntityManagerInterface
     */
    protected $manager;
    /**
     * @var Pagination
     */
    protected $pagination;

    public function __construct(CommentRepository $repository, EntityManagerInterface $manager, Pagination $pagination)
    {
        $this->repository = $repository;
        $this->manager    = $manager;
        $this->pagination = $pagination;
    }

    /**
     * @param int $page
     * @return Response
     */
    public function index(int $page): Response
    {
        $this->pagination->setEntityClass(Comment::class)->setCurrentPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $this->pagination
        ]);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', "Le commentaire a bien été modifiée");

            return $this->redirectToRoute('admin_comments');
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form'    => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete_comment' . $comment->getId(), $request->get('_token'))) {
            $this->manager->remove($comment);
            $this->manager->flush();

            $this->addFlash('success', "Commentaire <strong>{$comment->getId()}</strong> supprimée");
        }

        return $this->redirectToRoute('admin_comments');
    }
}
