<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/song', name: 'app_post_song', methods: ['POST'])]
class PostSongController
{
    public function __invoke(Request $request, Environment $twig): Response
    {
        // handle request

        return (new Response($twig->render('post_song.html.twig')))->setMaxAge(3600);
    }
}
