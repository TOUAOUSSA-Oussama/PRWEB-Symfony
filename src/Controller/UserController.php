<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RepositoryPerson;
use App\Repository\RepositoryBook;
use App\Repository\RepositoryBorrow;
use App\Entity\Person;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{   
    /**
     * @Route("/users", name="users")
     * redireger vers la page de la liste des utilisateurs
     */
    public function users(RepositoryPerson $personRespository): Response
    {   

        return $this->render('user/index.html.twig', [
            'people' => $personRespository->findAll(),
        ]);
    }

    // /**
    //  * @Route("/users", name="users", methods={"GET"})
    //  */
    // public function index(): Response
    // {
    //     return $this->render('index/index.html.twig', []);
    // }

    /**
     * @Route("/editUser", name="editUser", methods={"POST"})
     * pour mettre a jour une personne
     */
    public function editUser(Request $request, RepositoryPerson $personRespository, 
                                                RepositoryBook $bookRepository, 
                                                RepositoryBorrow $borrowRepository): Response
    {   
        // mettre a jour les donnees perso de cette personne
        $person_id = $request->request->get("id");
        
        // mettre a jour les livres empruntes
        $books = $bookRepository->findAll();
        $booksBorrowed = $borrowRepository->findByUser($person_id);

        return $this->render('user/edit.html.twig', [
            'person' => $personRespository->find($person_id),
            'books' => $books,
            'borrows' => $booksBorrowed,
        ]);
    }

    /**
     * @Route("/saveUser", name="saveUser", methods={"POST"})
     * permet d'enregistrer les nouveaux modifications
     */
    public function saveUser(Request $request, RepositoryPerson $personRespository): Response
    {
        $person_id = $request->request->get("id");
        $FirstName = $request->request->get("FirstName");
        $LastName = $request->request->get("LastName");
        $BirthDay = $request->request->get("Birthdate");
        $BirthDay = \DateTime::createFromFormat("Y-m-d", $BirthDay);

        $em = $this->getDoctrine()->getManager();
        if($person_id > 0){
            $person = $personRespository->find($person_id);
        } else {
            $person = new Person();
            $em->persist($person);
        }
        $person->setPersonFirstname($FirstName);
        $person->setPersonLastName($LastName);
        $person->setPersonBirthdate($BirthDay);
        $em->flush();

        return $this->render('user/index.html.twig', [
            'people' => $personRespository->findAll(),
        ]);
    }

    /**
     * @Route("/addUser", name="addUser", methods={"POST"})
     * Permet d'ajouter un nouveau person
     */
    public function addUser(): Response
    {
        $person = new Person();
        return $this->render('user/edit.html.twig', [
            'person' => $person,
            'borrows' => null,
        ]);
    }

    /**
     * @Route("/removeUser", name="removeUser", methods={"POST"})
     * permet de supprimer un utilisateur
     */
    public function removeUser(Request $request, RepositoryPerson $personRespository): Response
    {
        $person_id = $request->request->get("id");
        $person = $personRespository->find($person_id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($person);
        $em->flush();

        return $this->render('user/index.html.twig', [
            'people' => $personRespository->findAll(),
        ]);
    } 

}

