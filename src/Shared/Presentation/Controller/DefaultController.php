<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '')]
class DefaultController extends AbstractController
{
    public function __construct(
        readonly private LoggerInterface $logger
    ) {
    }

    #[Route(path: '/', name: 'app.shared.default', methods: ['GET'])]
    public function index(): Response
    {
        $this->logger->notice('Hello log world!');

        return new Response('Hello world!');
    }

    #[Route(path: '/twig', name: 'app.shared.twig', methods: ['GET'])]
    public function twig(): Response
    {
        return $this->render(
            'index.html.twig', [
                'page_title' => 'I am title!',
            ]
        );
    }
}
