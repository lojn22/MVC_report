<?php

namespace App\Tests\Controller;

use App\Controller\LibraryController;
use App\Entity\Library;
use App\Repository\LibraryRepository;
use App\Service\FileUploader;
use App\Service\LibraryService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LibraryControllerTest extends TestCase
{
    public function testIndexRendersTemplate()
    {
        $controller = $this->getMockBuilder(LibraryController::class)
            ->onlyMethods(['render'])
            ->getMock();

        $controller->method('render')->willReturn(new Response('LibraryController'));

        $response = $controller->index();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('LibraryController', $response->getContent());
    }

    public function testCreateBook_NotSubmittedRendersForm()
    {
        $request = new Request();
        $fileUploader = $this->createMock(FileUploader::class);
        $libraryService = $this->createMock(LibraryService::class);

        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(false);
        $form->method('createView')->willReturn(new FormView());

        $controller = $this->getMockBuilder(LibraryController::class)
            ->onlyMethods(['createForm', 'render'])
            ->getMock();

        $controller->method('createForm')->willReturn($form);
        $controller->method('render')->willReturn(new Response('form_view'));

        $response = $controller->createBook($request, $fileUploader, $libraryService);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('form_view', $response->getContent());
    }

    public function testViewOneBook_ReturnsBook()
    {
        $repo = $this->createMock(LibraryRepository::class);
        $repo->method('find')->willReturn(new Library());

        $controller = $this->getMockBuilder(LibraryController::class)
            ->onlyMethods(['render'])
            ->getMock();

        $controller->method('render')->willReturn(new Response('book_view'));

        $response = $controller->viewOneBook($repo, 1);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('book_view', $response->getContent());
    }

    public function testViewOneBook_ThrowsExceptionIfNotFound()
    {
        $repo = $this->createMock(LibraryRepository::class);
        $repo->method('find')->willReturn(null);

        $controller = new LibraryController();

        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);
        $controller->viewOneBook($repo, 999);
    }

    public function testResetLibrary_ReturnsResponse()
    {
        $service = $this->createMock(LibraryService::class);
        $service->expects($this->once())->method('reset');

        $controller = new LibraryController();

        $response = $controller->resetLibrary($service);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('Biblioteket har återställts', $response->getContent());
    }
}
