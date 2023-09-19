<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book_index',methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/{!id<\d+>?1}', name: 'app_book_show')]
    public function show(int $id): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController - id : '.$id,
        ]);
    }
}
