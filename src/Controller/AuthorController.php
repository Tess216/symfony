<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
/* #[Route('/author/{name}', name: 'app_author')]
    public function index($name): Response
    {
        $nickname = 'm'.$name;
        return $this->render('author/index.html.twig', [
            'name' => $nickname,
        ]);
    }
*/

//liste auteur
    #[Route('/list', name: 'list_author')]
    public function list(AuthorRepository $authrepo): Response
    {
        $authors =$authrepo->findAll();
        return $this->render('author/list.html.twig', ['authors'=> $authors
        ]);
    }
//ajouter auteur
    #[Route('/authoradd',name: 'addauthor')]
    public function addAuthor(ManagerRegistry $doctrine, Request $request):Response
    {
        $author = New Author;
       //formulaire
       $form = $this->createForm(AuthorType::class,$author);
       $form ->add('Ajouter',SubmitType::class);
       $form->handleRequest($request);
       $em=$doctrine->getManager();
       if($form->isSubmitted()){

        
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute('list_author');
    
    }
        return $this->render('author/addAuthor.html.twig', ['FormA'=> $form->createView()

    ]);
    }

//modifier auteur
    #[Route('updateauthor/{id}',name: 'updateAuthor')]
    public function UpdateAuthor($id, ManagerRegistry $doctrine, Request $request ):Response
    {
        $repo=$doctrine->getRepository(Author::class);
        $author = $repo->find($id);
       //formulaire
       $form = $this->createForm(AuthorType::class,$author);
       $form ->add('Modifier',SubmitType::class);
       $form->handleRequest($request);
       $em=$doctrine->getManager();
       if($form->isSubmitted()){

        
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute('list_author');
    
    }
        return $this->render('author/addAuthor.html.twig', ['FormA'=> $form->createView()

    ]);
    }

//detail auteur
    #[Route('/author/{id}' ,name:'showauthor')]
    public function author ($id, AuthorRepository $repo):Response
    {
    $author = $repo->find($id);
        return $this->render('author/detailauthor.html.twig',['author'=>$author
    ]);
    }


    #l doctrine orm howa li ymanipuli fl base de donnes

//supression auteur
    #[Route('/deleteAuthor/{id}',name:'deleteAuthor',methods:['POST'])]
    public function deleteAuthor ($id, AuthorRepository $repo ,EntityManagerInterface $em):Response
    {
        $author = $repo->find($id);
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('list_author');

    }




  /*2eme methode
   #[Route('/deleteauthor/{id}' ,name:'deleteAuthor')]
   public function deleteauthor ($id, ManagerRegistry $doctrine):Response
   {
       $repo=$doctrine->getRepository(Author::class);
       $em=$doctrine->getManager();
       $author = $repo->find($id);
       $em->remove($author);
       $em->flush();
       return $this->redirectToRoute('list_author');
   }*/
}
