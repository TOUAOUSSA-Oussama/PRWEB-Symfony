<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function login(Request $request): Response 
    {
        $login = $request->request->get("login");
        $passwd = $request->request->get("password");
        
        $isAuthenticate = $this->authenticate($login, $passwd);

        if($isAuthenticate){
            return $this->forward('App\Controller\UserController::users');
        } else {
            return $this->redirectToRoute('index');
        }
    }

    private function authenticate(string $login, string $passwd): bool
    {
        if ($login=="admin" && $passwd=="admin"){
            return true;
        } else {
            return false;
        }
    }
}