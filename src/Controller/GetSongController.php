<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_MODERATOR')]
#[Route('/song/{id<\d+>}', name: 'app_song_get')]
class GetSongController
{
    public function __invoke(int $id): Response
    {
        return new JsonResponse([
            'message' => 'I\'m a controller!',
            'controller' => self::class,
        ]);
    }
}
