<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/{!id?1}', name: 'app_book_show')]
    public function show(string $id): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController - id : '.$id,
        ]);
    }

    #[Route('/new', name: 'app_book_new')]
    public function save(Request $request, EntityManagerInterface $manager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $manager->persist($book);
            $manager->flush();

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('book/save.html.twig', [
            'form' => $form,
        ]);
    }
}
