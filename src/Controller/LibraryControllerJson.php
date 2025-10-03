<?php

namespace App\Controller;

use App\Entity\Library;
use App\Form\BookType;
use App\Repository\LibraryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class LibraryControllerJson extends AbstractController
{
    #[Route('api/library/books', name: 'api_books')]
    public function booksJson(
        ManagerRegistry $doctrine,
    ): JsonResponse {
        $books = $doctrine->getRepository(Library::class)->findAll();

        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'isbn' => $book->getISBN(),
                'image' => $book->getImage(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/library/show', name: 'library_show_all')]
    public function showAllProduct(
        LibraryRepository $libraryRepository,
    ): Response {
        $books = $libraryRepository->findAll();

        // return $this->json($books);
        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('api/library/books/{isbn}', name: 'api_book_by_isbn', methods: ['GET'])]
    public function booksJsonByIsbn(
        ManagerRegistry $doctrine,
        string $isbn,
    ): JsonResponse {
        $book = $doctrine->getRepository(Library::class)->findOneBy(['ISBN' => $isbn]);

        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], 404);
        }

        $data = [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'isbn' => $book->getISBN(),
            'image' => $book->getImage(),
        ];

        return new JsonResponse($data);
    }

    #[Route('/library/show/{id}', name: 'book_by_id')]
    public function showProductById(
        LibraryRepository $libraryRepository,
        int $id,
    ): Response {
        $book = $libraryRepository
            ->find($id);

        return $this->json($book);
    }

    #[Route('/library/show/min/{value}', name: 'book_by_min_value')]
    public function showProductByMinimumValue(
        LibraryRepository $libraryRepository,
        int $value,
    ): Response {
        $books = $libraryRepository->findByMinimumValue2($value);

        return $this->json($books);
    }
}
