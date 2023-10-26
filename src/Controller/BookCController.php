<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookCController extends AbstractController
{
    #[Route('/listbook', name: 'listbook')]
    public function ListBook(BookRepository $repo ,ManagerRegistry $doctrine): Response
    {
        $publishedBooks = $repo->findBy(['published'=> true] );
        $unpublishedBooks = $repo->findBy(['published'=> false] );

        $numpublished = count($publishedBooks);
        $num_unpublished = count($unpublishedBooks);
        $books = $repo->findAll();
        return $this->render('book_c/listbook.html.twig', [
            'books' => $books, 'numpublished'=>$numpublished, 'num_unpublished'=>$num_unpublished
        ]);
    }

    #[Route('/addbook',name:'addbook')]
    public function addbook(ManagerRegistry $doctrine ,Request $request):Response
    {
        $book = new Book;
        $book->setPublished(true);
        $form = $this->createForm(BookType::class ,$book);
        $form ->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
    
        $em=$doctrine->getManager();
        if($form->isSubmitted()){
            
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('listbook');
        }
        return $this->render('book_c/addeditbook.html.twig',['FormA'=>$form->createView()]);
    }

    #[Route('/updatebook{ref}',name:'updatebook')]
    
    public function updatebook($ref,ManagerRegistry $doctrine ,Request $request ,BookRepository $repo):Response
    {
        
        $book =$repo->find($ref);
        $form = $this->createForm(BookType::class ,$book);
        $form ->add('Modifier',SubmitType::class);
        $form->handleRequest($request);

        $em=$doctrine->getManager();
        if($form->isSubmitted()){
            
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('listbook');
        }
        return $this->render('book_c/addeditbook.html.twig',['FormA'=>$form->createView()]);
    }

    #[Route('deletebook/{ref}',name:'deletebook')]
    public function deletebook ($ref ,ManagerRegistry $doctrine ,BookRepository $repo): Response
    {
        $book =$repo->find($ref);
        $em = $doctrine->getManager();
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('listbook');

    }
    #[Route('showbook/{ref}',name:'showbook')]
    public function showbook($ref ,BookRepository $repo ):Response
    {
        $book = $repo ->find($ref);
        return $this->render('book_c/bookDetails.html.twig',['book'=>$book]);

    }

    

}

