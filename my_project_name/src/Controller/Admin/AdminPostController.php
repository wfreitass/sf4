<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/admin/posts")
 */
class AdminPostController extends AbstractController
{
    /**
     * @Route("/", name="admin_post")
     */
    public function index(): Response
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();
        return $this->render('admin/posts/index.html.twig', [
            "posts" => $posts
        ]);
    }

    /**
     * @Route("/new", name="admin_post_new")
     */
    public function new(Request $request)
    {
        $form = $this->createForm(PostType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_paulo")));
            $post->setUpdatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_paulo")));
          
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash("success", "Post Salvo com sucesso");
            return $this->redirectToRoute("admin_post");

        }

        return $this->render('admin/posts/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

     /**
     * @Route("/update/{id}", name="admin_post_update")
     */
    public function update(Request $request, Post $id){
        
        $form = $this->createForm(PostType::class, $id);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            
            $post->setUpdatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_paulo")));
          
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($post);
            $entityManager->flush();

            $this->addFlash("success", "Post Atualizado com sucesso");
            return $this->redirectToRoute("admin_post");

        }

        return $this->render('admin/posts/update.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_post_delete")
     */
    public function delete(Post $post){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash("success", "Post Deletado com sucesso");
        return $this->redirectToRoute("admin_post");
    }
}
