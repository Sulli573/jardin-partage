<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository): Response
    {
        /** @var Post[] $posts */
        $posts = $postRepository->findBy([], ['createdAt'=>'desc']);
        // tableau qui va contenir tous les formulaires des commentaires (1 commentaire par formulaire)
        $commentForm = [];
        // boucler sur les posts
        foreach($posts as $post) {
            // Objectif créer le formulaire de commentaire pour chaque post
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment,[
                'action'=> $this->generateUrl('app_comment_new', [
                    'id'=>$post->getId(),
                ]),
            ]);
            $commentForm[$post->getId()] = $form->createView();
        }
        return $this->render('home/index.html.twig', [
            // je donne à la vue les posts et le formulaire de chaque commentaire pour chaque post
            'posts' => $posts,
            // tous les formulaires
            'commentForm' => $commentForm,
        ]);
    }
}
