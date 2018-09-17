<?php

namespace PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    public function listAction () {
        $pageRepo = $this->getDoctrine()->getRepository('PageBundle:Page');
        $pages = $pageRepo->findAll();

        return $this->render('PageBundle:Page:list.html.twig', [
            'pages' => $pages
        ]);
    }

    public function viewAction ($id) {
        $pageRepo = $this->getDoctrine()->getRepository('PageBundle:Page');
        $page = $pageRepo->find($id);

        if (!$page) {
            throw $this->createNotFoundException("Page not found!");
        }

        return $this->render('PageBundle:Page:view.html.twig', [
            'page' => $page
        ]);
    }
}