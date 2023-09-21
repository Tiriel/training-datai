<?php

namespace App\Book;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BookManager
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        #[Autowire(param: 'app.items_per_page')]
        private readonly int $itemsPerPage,
    ) {}

    public function findBookByTitle(string $title): Book
    {
        return $this->manager->getRepository(Book::class)->findOneBy(['title' => $title]);
    }

    public function findBookById(int $id): Book
    {
        return $this->manager->find(Book::class, $id);
    }

    public function getBookListPaginated(int $offset): iterable
    {
        return $this->manager->getRepository(Book::class)->findBy([], [], $this->itemsPerPage, $offset);
    }
}
