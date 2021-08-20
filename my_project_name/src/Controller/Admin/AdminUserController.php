<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/admin/users")
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/", name="admin_user")
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        return $this->render('admin/users/index.html.twig', [
            "users" => $users
        ]);
    }

    /**
     * @Route("/new", name="admin_user_new")
     */
    public function new(Request $request)
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_paulo")));
            $post->setUpdatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_paulo")));
          
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash("success", "Usuário Salvo com sucesso");
            return $this->redirectToRoute("admin_user");

        }

        return $this->render('admin/users/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

     /**
     * @Route("/update/{id}", name="admin_user_update")
     */
    public function update(Request $request, User $id){
        
        $form = $this->createForm(UserType::class, $id);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            
            $post->setUpdatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_paulo")));
          
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($post);
            $entityManager->flush();

            $this->addFlash("success", "Usuário Atualizado com sucesso");
            return $this->redirectToRoute("admin_user");

        }

        return $this->render('admin/users/update.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_user_delete")
     */
    public function delete(User $user){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash("success", "Usuário Deletado com sucesso");
        return $this->redirectToRoute("admin_user");
    }
}
