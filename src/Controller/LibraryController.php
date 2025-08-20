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
        ManagerRegistry $doctrine,
    ): Response {
        $book = new Library();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = preg_replace('/[^a-zA-Z0-9-_]/', '', $originalFilename);
                $newFilename = strtolower($safeFilename) . '-' . uniqid() . '.' . $imageFile->guessExtension();

                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';

                if (!file_exists($destination)) {
                    mkdir($destination, 0777, true);
                }

                try {
                    $imageFile->move($destination, $newFilename);
                } catch (FileException $e) {
                    throw new \Exception('Fel vid uppladdning av fil: ' . $e->getMessage());
                }

                $book->setImage('uploads/' . $newFilename);
            }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_success');
        }

        return $this->render('library/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/library/success', name: 'book_success')]
    public function bookSuccess(): Response
    {
        return new Response('<h1>Boken har sparats!</h1>');
    }

    #[Route('/library/show', name: 'library_show_all')]
    public function showAllProduct(
        LibraryRepository $libraryRepository,
    ): Response {
        $books = $libraryRepository
            ->findAll();

        // return $this->json($books);
        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
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

    #[Route('/library/delete/{id}', name: 'book_delete_by_id')]
    public function deleteBookById(
        ManagerRegistry $doctrine,
        int $id,
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/update/{id}', name: 'book_update', methods: ['GET', 'POST'])]
    public function updateBook(
        Request $request,
        ManagerRegistry $doctrine,
        int $id,
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'The book is updated');

            return $this->redirectToRoute('library_show_all');
        }

        // $book->setISBN($value);
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
            throw $this->ceraNotFoundException('There is no more information about the book.');
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

    #[Route('/library/show/min/{value}', name: 'book_by_min_value')]
    public function showProductByMinimumValue(
        LibraryRepository $libraryRepository,
        int $value,
    ): Response {
        $books = $libraryRepository->findByMinimumValue2($value);

        return $this->json($books);
    }

    #[Route('/library/reset', name: 'library_reset')]
    public function resetLibrary(
        ManagerRegistry $doctrine,
    ): Response {
        $entityManager = $doctrine->getManager();
        $repo = $entityManager->getRepository(Library::class);

        // Ta bort befintliga böcker
        $books = $repo->findAll();
        foreach ($books as $book) {
            $entityManager->remove($book);
        }
        $entityManager->flush();

        // Default innehåll, minst 3st böcker
        $book1 = new Library();
        $book1->setTitle('Spelet');
        $book1->setISBN(9789189750272);
        $book1->setAuthor('Elle Kennedy');
        $book1->setImage('img/spelet.png');

        $book2 = new Library();
        $book2->setTitle('Snedsteget');
        $book2->setISBN(9789189889606);
        $book2->setAuthor('Elle Kennedy');
        $book2->setImage('img/snedsteget.png');

        $book3 = new Library();
        $book3->setTitle('Uppgörelsen');
        $book3->setISBN(9789189928015);
        $book3->setAuthor('Elle Kennedy');
        $book3->setImage('img/uppgorelsen.png');

        $book4 = new Library();
        $book4->setTitle('Målet');
        $book4->setISBN(9789189889552);
        $book4->setAuthor('Elle Kennedy');
        $book4->setImage('img/malet.png');

        $book5 = new Library();
        $book5->setTitle('Finalen');
        $book5->setISBN(9789189928206);
        $book5->setAuthor('Elle Kennedy');
        $book5->setImage('img/finalen.png');

        $entityManager->persist($book1);
        $entityManager->persist($book2);
        $entityManager->persist($book3);
        $entityManager->persist($book4);
        $entityManager->persist($book5);
        $entityManager->flush();

        return new Response('<html><body>Biblioteket har återställts.</body></html>');
    }
}
