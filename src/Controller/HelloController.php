<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/hello/{name<[\pL -]+>}', name: 'app_hello')]
    public function index(string $name, #[Autowire(param: 'sf_version')] string $sfVersion): Response
    {
        dump($sfVersion);
        // dump(json_decode(file_get_contents(__DIR__.'/../../composer.json'))['extra']['symfony']['require']);

        return $this->render('hello/index.html.twig', [
            'controller_name' => $name,
        ]);
    }
}
