<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

#[AsEventListener(event: KernelEvents::REQUEST, priority: 9999)]
class MaintenanceListener
{
    private bool $isMaintenance;

    public function __construct(
        #[Autowire(param: 'env(bool:APP_MAINTENANCE)')]
        bool $isMaintenance,
        private readonly Environment $twig
    ) {
        $this->isMaintenance = $isMaintenance;
    }

    public function __invoke(RequestEvent $event): void
    {
        if ($this->isMaintenance) {
            $response = new Response();
            if ($event->isMainRequest()) {
                $response->setContent($this->twig->render('maintenance.html.twig'));
            }
            $event->setResponse($response);
        }
    }
}
