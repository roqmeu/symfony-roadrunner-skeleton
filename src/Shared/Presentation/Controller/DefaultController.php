<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Controller;

use App\Shared\Presentation\Messenger\FailedEvent;
use App\Shared\Presentation\Messenger\SimpleEvent;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '')]
class DefaultController extends AbstractController
{
    public function __construct(
        readonly private MessageBusInterface $messageBus,
        readonly private LoggerInterface $logger,
        readonly private CacheItemPoolInterface $roadrunnerMemoryCache,
        readonly private CacheItemPoolInterface $roadrunnerMemcachedCache,
        readonly private CacheItemPoolInterface $symfonyMemcachedCache,
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
        $this->logger->info('Received app.shared.twig');

        return $this->render(
            'index.html.twig', [
                'page_title' => 'I am title!',
            ]
        );
    }

    #[Route(path: '/cache', name: 'app.shared.cache', methods: ['GET'])]
    public function cache(): Response
    {
        $this->logger->info('Received app.shared.cache');

        $response = '';
        $this->roadrunnerMemoryCache->clear();

        $time = microtime(true);
        for ($i = 0; $i < 10000; $i++) {
            $k = 'rr_me_' . $i;

            $item = $this->roadrunnerMemoryCache->getItem($k);

            $item->set($k);
            $item->expiresAfter(3600);

            $this->roadrunnerMemoryCache->save($item);
            $this->roadrunnerMemoryCache->getItem($k);
        }
        $response .= 'roadrunnerMemoryCache -> ' . microtime(true) - $time . '<br/>';

        $this->roadrunnerMemcachedCache->clear();

        $time = microtime(true);
        for ($i = 0; $i < 10000; $i++) {
            $k = 'rr_mm_' . $i;

            $item = $this->roadrunnerMemcachedCache->getItem($k);

            $item->set($k);
            $item->expiresAfter(3600);

            $this->roadrunnerMemcachedCache->save($item);
            $this->roadrunnerMemcachedCache->getItem($k);
        }
        $response .= 'roadrunnerMemcachedCache -> ' . microtime(true) - $time . '<br/>';

        $this->symfonyMemcachedCache->clear();

        $time = microtime(true);
        for ($i = 0; $i < 10000; $i++) {
            $k = 'sy_mm_' . $i;

            $item = $this->symfonyMemcachedCache->getItem($k);

            $item->set($k);
            $item->expiresAfter(3600);

            $this->symfonyMemcachedCache->save($item);
            $this->symfonyMemcachedCache->getItem($k);
        }
        $response .= 'symfonyMemcachedCache -> ' . microtime(true) - $time . '<br/>';

        $this->symfonyMemcachedCache->clear();

        $time = microtime(true);
        for ($i = 0; $i < 10000; $i++) {
            $k = 'na_mm_' . $i;

            if (0 === $i % 5) {
                // Reconnection emulation
                $connect = new \Memcached();
                $connect->addServer('/var/run/shared/memcached/memcached.sock', 0);
                $connect->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
                $connect->setOption(\Memcached::OPT_NO_BLOCK, true);
                $connect->setOption(\Memcached::OPT_TCP_NODELAY, true);
            }

            $connect->get($k);
            $connect->getResultCode() === \Memcached::RES_NOTFOUND;
            $connect->set($k, $k, 3600);
            $connect->get($k);
        }
        $response .= 'nativeMemcachedCache -> ' . microtime(true) - $time . '<br/>';

        return new Response($response);
    }

    #[Route(path: '/message', name: 'app.shared.message', methods: ['GET'])]
    public function message(): Response
    {
        $this->logger->info('Received app.shared.message');

        $this->messageBus->dispatch(new SimpleEvent('Message!'));
        $this->messageBus->dispatch(new SimpleEvent('Delayed message!'), [new DelayStamp(5000)]);

        $this->messageBus->dispatch(new FailedEvent('Failed message!'));
        $this->messageBus->dispatch(new FailedEvent('Failed delayed message!'), [new DelayStamp(5000)]);

        $this->logger->info('Dispatch app.shared.message');

        return new Response('Send!');
    }
}
