<?php

namespace App\Controller;

use App\Entity\Borrow;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\RepositoryBook;
use App\Repository\RepositoryPerson;
use App\Repository\RepositoryBorrow;

class BorrowController extends AbstractController
{
    /**
     * @Route("/addBook", name="addBook", methods={"POST"})
     * permet d'ajouter un book emprunte
     */
    public function addBook(Request $request, RepositoryBook $bookRepository, RepositoryPerson $personRespository, RepositoryBorrow $borrowRepository): Response
    {
        $personId = $request->request->get("personId");
        $person = $personRespository->find($personId);

        $bookID = $request->request->get("bookID");
        $book = $bookRepository->find($bookID);

        $borrowDate = new \DateTime('now');

        $em = $this->getDoctrine()->getManager();
        $borrowBook = new Borrow();
        $em->persist($borrowBook);
        
        $borrowBook->setPerson($person);
        $borrowBook->setBook($book);
        $borrowBook->setBorrowDate($borrowDate);
        $em->flush();

        // retoruner la page d'editUSer
        // $person_id = $request->request->get("personId");
        // $books = $bookRepository->findAll();
        // $booksBorrowed = $borrowRepository->findByUser($person_id);

        // return $this->render('user/edit.html.twig', [
        //     'person' => $personRespository->find($person_id),
        //     'books' => $books,
        //     'borrows' => $booksBorrowed,
        // ]);
        return $this->forward('App\Controller\UserController::users');
    }

    /**
     * @Route("/returnBorrow", name="returnBorrow", methods={"POST"})
     * permet de metter a jour la date de retour du livre dans la base de donnees
     * les donnees sont recuperes depuis la fonciton definie dans js/script.js
     */
    public function returnBorrow(Request $request, RepositoryBorrow $borrowRepository): Response
    {
        $idValue = $request->request->get("id");

        $em = $this->getDoctrine()->getManager();
        if( $idValue > 0) {
            $returnedDate = new \DateTime('now');

            $borrow = $borrowRepository->find($idValue);
            $borrow->setBorrowReturn($returnedDate);
            $em->flush();

            $data = ['returnedValue' => $returnedDate];
            return new JsonResponse($data);
        } else {
            return new JsonResponse('no  results found', Response::HTTP_NOT_FOUND);
        }
    }
}
