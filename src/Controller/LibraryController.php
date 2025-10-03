<?php

namespace App\Controller;

use App\Entity\Library;
use App\Form\BookType;
use App\Repository\LibraryRepository;
use App\Service\FileUploader;
use App\Service\LibraryService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/create', name: 'book_create')]
    public function createBook(
        Request $request,
        FileUploader $fileUploader,
        LibraryService $libraryService
    ): Response {
        $book = new Library();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            $libraryService->handleUploadImage($imageFile, $book, $fileUploader);
            $libraryService->saveBook($book);

            // return $this->redirectToRoute('book_success');
            return $this->redirectToRoute('library_view_all');
        }

        return $this->render('library/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/library/success', name: 'book_success')]
    // public function bookSuccess(): Response
    // {
    //     return new Response('<h1>Boken har sparats!</h1>');
    // }

    #[Route('/library/delete/{id}', name: 'book_delete_by_id')]
    public function deleteBookById(
        LibraryService $libraryService,
        int $id,
    ): Response {
        $libraryService->deleteBookById($id);
        return $this->redirectToRoute('library_view_all');
    }

    #[Route('/library/update/{id}', name: 'book_update', methods: ['GET', 'POST'])]
    public function updateBook(
        Request $request,
        LibraryService $libraryService,
        FileUploader $fileUploader,
        int $id,
    ): Response {
        $book = $libraryService->findBook($id);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $libraryService->handleUploadImage($imageFile, $book, $fileUploader);
            }
            $libraryService-> updateBook();

            // $this->addFlash('success', 'The book is updated');
            return $this->redirectToRoute('library_view_all');
        }

        return $this->render('library/update.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route('/library/view', name: 'library_view_all')]
    public function viewAllBooks(
        LibraryRepository $libraryRepository,
    ): Response {
        $books = $libraryRepository->findAll();
        $data = [
            'books' => $books,
        ];

        return $this->render('library/view.html.twig', $data);
    }

    #[Route('library/book/{id}', name: 'book_view_one')]
    public function viewOneBook(
        LibraryRepository $libraryRepository,
        int $id,
    ): Response {
        $book = $libraryRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('There is no more information about the book.');
        }

        return $this->render('library/viewOne.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/view/{value}', name: 'book_view_min_value')]
    public function viewProductWithMinimumValue(
        LibraryRepository $libraryRepository,
        int $value,
    ): Response {
        $books = $libraryRepository->findByMinimumValue($value);

        $data = [
            'books' => $books,
        ];

        return $this->render('library/view.html.twig', $data);
    }

    #[Route('/library/reset', name: 'library_reset')]
    public function resetLibrary(
        LibraryService $libraryService,
    ): Response {
        $libraryService->reset();
        return new Response('<html><body>Biblioteket har återställts.</body></html>');
    }
}
