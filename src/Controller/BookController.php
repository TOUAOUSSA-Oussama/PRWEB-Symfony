<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RepositoryBook;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{   
    /**
     * @Route("/books", name="books")
     * redireger vers la page de la liste des books
     */
    public function users(RepositoryBook $bookRespository): Response
    {   

        return $this->render('book/index.html.twig', [
            'books' => $bookRespository->findAll(),
        ]);
    }

    // /**
    //  * @Route("/books", name="books", methods={"GET"})
    //  */
    // public function index(): Response
    // {
    //     return $this->render('index/index.html.twig', []);
    // }

    /**
     * @Route("/editBook", name="editBook", methods={"POST"})
     * pour mettre a jour un book
     */
    public function editBook(Request $request, RepositoryBook $bookRespository): Response
    {   
        $book_id = $request->request->get("id");
        return $this->render('book/edit.html.twig', [
            'book' => $bookRespository->find($book_id),
        ]);
    }

    /**
     * @Route("/saveBook", name="saveBook", methods={"POST"})
     * permet d'enregistrer les nouveaux modifications
     */
    public function saveBook(Request $request, RepositoryBook $bookRespository): Response
    {
        $book_id = $request->request->get("id");
        $Title = $request->request->get("Title");
        $Authors = $request->request->get("Authors");

        $em = $this->getDoctrine()->getManager();
        if($book_id > 0){
            $book = $bookRespository->find($book_id);
        } else {
            $book = new Book();
            $em->persist($book);
        }
        $book->setBookTitle($Title);
        $book->setBookAuthors($Authors);
        $em->flush();

        return $this->render('book/index.html.twig', [
            'books' => $bookRespository->findAll(),
        ]);
    }

    /**
     * @Route("/addNewBook", name="addNewBook", methods={"POST"})
     * Permet d'ajouter un nouveau book
     */
    public function addBook(): Response
    {
        $book = new Book();
        return $this->render('book/edit.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/removeBook", name="removeBook", methods={"POST"})
     * permet de supprimer un book
     */
    public function removeBook(Request $request, RepositoryBook $bookRespository): Response
    {
        $book_id = $request->request->get("id");
        $book = $bookRespository->find($book_id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();

        return $this->render('book/index.html.twig', [
            'books' => $bookRespository->findAll(),
        ]);
    } 

}
