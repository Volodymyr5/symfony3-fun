<?php

namespace TermBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlocksController extends Controller
{
    public function categoryAction()
    {
        $termRepo = $this->getDoctrine()->getRepository('TermBundle:Term');
        $terms = $termRepo->findAll();

        return $this->render('@Term/Block/category-list.html.twig', [
            'terms' => $terms
        ]);
    }
}