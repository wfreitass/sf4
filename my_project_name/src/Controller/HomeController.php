<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        // return $this->render('home/index.html.twig', [
        //     'controller_name' => 'HomeController',
        // ]);
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        echo "<pre>", print_r($posts, true), "</pre>";
        return new Response("SEJA bem vindo");
    }
}
