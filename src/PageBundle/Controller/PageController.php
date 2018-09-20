<?php

namespace PageBundle\Controller;

use CommentBundle\Entity\Comment;
use CommentBundle\Forms\CommentForm;
use PageBundle\Entity\Page;
use PageBundle\Forms\PageDeleteForm;
use PageBundle\Forms\PageForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function listAction(Request $request)
    {
        $pageRepo = $this->getDoctrine()->getRepository('PageBundle:Page');

        $pager = [
            'pager' => $request->query->get('page') ? $request->query->get('page') : 1,
            'limit' => $request->query->get('limit') ? $request->query->get('limit') : 2,
            'total' => $pageRepo->countPages()
        ];

        $pages = $pageRepo->findPages($pager['pager'], $pager['limit']);

        return $this->render('PageBundle:Page:list.html.twig', [
            'pages' => $pages,
            'navigator' => $pager
        ]);
    }

    public function viewAction($id, Request $request)
    {
        $pageRepo = $this->getDoctrine()->getRepository('PageBundle:Page');
        $page = $pageRepo->find($id);

        if (!$page) {
            throw $this->createNotFoundException("Page not found!");
        }

        $em = $this->getDoctrine()->getManager();

        $commentForm = $this->createForm(CommentForm::class);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted()) {
            /** @var Comment $comment */
            $comment = $commentForm->getData();
            $comment->setPage($page);
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('page_view', ['id' => $page->getId()]);
        }

        $commentRepo = $em->getRepository(Comment::class);
        $comments = $commentRepo->findLastComments($page, 10);


        return $this->render('PageBundle:Page:view.html.twig', [
            'page' => $page,
            'comment_form' => $commentForm->createView(),
            'page_comments' => $comments
        ]);
    }

    public function commentsAction (Request $request, $id)
    {
        $pageRepo = $this->getDoctrine()->getRepository('PageBundle:Page');
        $page = $pageRepo->find($id);

        if (!$page) {
            throw $this->createNotFoundException("Page not found!");
        }

        $commentRepo = $this->getDoctrine()->getRepository('CommentBundle:Comment');

        $pager = [
            'pager' => $request->query->get('pager') ? $request->query->get('pager') : 1,
            'limit' => $request->query->get('limit') ? $request->query->get('limit') : 10,
            'total' => $commentRepo->countComments($page)
        ];

        $comments = $commentRepo->findComments($page, $pager['pager'], $pager['limit']);

        return $this->render('PageBundle:Page:page_comments.html.twig', [
            'page' => $page,
            'comments' => $comments,
            'navigator' => $pager,
        ]);
    }

    public function addAction(Request $request)
    {
        $page = new Page();
        $form = $this->createForm(PageForm::class, $page);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('page_list');
        }

        return $this->render('@Page/Page/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('PageBundle:Page');
        $page = $repo->find($id);
        if (!$page) {
            return $this->redirectToRoute('page_list');
        }

        $form = $this->createForm(PageForm::class, $page);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('page_view', ['id' => $page->getId()]);
        }

        return $this->render('@Page/Page/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function removeAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('PageBundle:Page');
        $page = $repo->find($id);
        if (!$page) {
            return $this->redirectToRoute('page_list');
        }

        $form = $this->createForm(PageDeleteForm::class, null, [
            'delete_id' => $page->getId()
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->remove($page);
            $em->flush();

            return $this->redirectToRoute('page_list');
        }

        return $this->render('@Page/Page/delete.html.twig', [
            'form' => $form->createView()
        ]);
    }
}