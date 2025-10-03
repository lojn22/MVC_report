<?php

namespace App\Service;

use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LibraryService 
{
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
    }

    public function findBook(int $id): Library
    {
        $book = $this->entityManager->getRepository(Library::class)->find($id);
        if (!$book) {
            throw new \InvalidArgumentException("No book found for id $id");
        }
        return $book;
    }

    public function saveBook(Library $book): void
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function updateBook(): void
    {
        $this->entityManager->flush();
    }

    public function deleteBookById(int $id): void
    {
        $book = $this->entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }

    public function handleUploadImage(?UploadedFile $imageFile, Library $book, FileUploader $fileUploader): void
    {
        if ($imageFile) {
                $newFilename = $fileUploader->upload($imageFile);
                $book->setImage('uploads/' . $newFilename);
            } else {
                $book->setImage('uploads/images.jpg');
            }
    }

    public function reset(): void
    {
        $repo = $this->entityManager->getRepository(Library::class);

        // Ta bort befintliga böcker
        $books = $repo->findAll();
        foreach ($books as $book) {
            $this->entityManager->remove($book);
        }

        $this->entityManager->flush();

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

        $this->entityManager->persist($book1);
        $this->entityManager->persist($book2);
        $this->entityManager->persist($book3);
        $this->entityManager->persist($book4);
        $this->entityManager->persist($book5);
        
        $this->entityManager->flush();
    }
}