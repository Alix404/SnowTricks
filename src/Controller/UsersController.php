<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/signUp", name="users.signUp")
     * @return Response
     */
    public function signUp(): Response
    {
        return $this->render('users/signUp.html.twig');
    }
}