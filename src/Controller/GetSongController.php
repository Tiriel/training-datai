<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/song', name: 'app_get_song')]
class GetSongController extends AbstractController
{
    public function __invoke()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return new Response('Yay');
    }
}
