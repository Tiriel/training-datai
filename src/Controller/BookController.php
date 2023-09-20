<?php

namespace App\Controller;

use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index',methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/{!id<\d+>?1}', name: 'app_book_show')]
    public function show(int $id): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController - id : '.$id,
        ]);
    }

    #[Route('/new', name: 'app_book_new')]
    public function save(): Response
    {
        $form = $this->createForm(BookType::class);

        return $this->render('book/save.html.twig', [
            'form' => $form,
        ]);
    }
}
