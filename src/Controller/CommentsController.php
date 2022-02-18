<?php

namespace App\Controller;

use App\Entity\Commentaires;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentsController extends AbstractController
{
    

    #[Route('/admin/comments', name: 'admin_comments_index')]
    public function adminIndex(CommentairesRepository $commentsRepository): Response
    {
        $comments = $commentsRepository->findAll();
        
        return $this->render('comments/index.html.twig', [
            'comments' => $comments,
        ]);
    }


    #[Route('/admin/comments/publish/{id}', name: 'comment_publish')]
    public function create(int $id, CommentairesRepository $commentsRepository, ManagerRegistry $managerRegistry)
    {
        $manager = $managerRegistry->getManager();
     
        $comment = $commentsRepository->find($id);
        $result= $comment->getPublish();
      
        if ($result == 0){
            $comment->setPublish('1');
           
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash('success', 'Le commentaire est publié');
            return $this->redirectToRoute('admin_comments_index');

        }elseif ($result == 1){
            $comment->setPublish('0');
           
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash('success', 'Le commentaire n\'est plus publié');
            return $this->redirectToRoute('admin_comments_index');
        }
    }

    #[Route('/admin/comments/delete/{id}', name: 'comment_delete')]
    public function delete(Commentaires $comments, ManagerRegistry $managerRegistry): Response
    {
        $manager = $managerRegistry->getManager();
                
        // suppression de l'objet en bdd
        $manager->remove($comments);
        $manager->flush();

        // redirection
        $this->addFlash('danger', 'Le commentaire a bien été supprimé');
        return $this->redirectToRoute('admin_comments_index');
    }

}
