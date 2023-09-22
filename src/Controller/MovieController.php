<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Form\MovieType;
use App\Movie\OmdbApiConsumer;
use App\Movie\Provider\MovieProvider;
use App\Movie\SearchTypeEnum;
use App\Movie\Transformer\OmdbMovieTransformer;
use App\Repository\MovieRepository;
use App\Security\Voter\MovieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(MovieRepository $repository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findAll(),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_movie_show')]
    public function show(?Movie $movie): Response
    {
        $this->denyAccessUnlessGranted(MovieVoter::VIEW, $movie);
        
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/omdb/{title}', name: 'app_movie_omdb', methods: ['GET'])]
    public function omdb(string $title, MovieProvider $provider): Response
    {
        $movie = $provider->getMovieByTitle($title);
        $this->denyAccessUnlessGranted(MovieVoter::VIEW, $movie);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/new', name: 'app_movie_new')]
    #[Route('/{id<\d+>}/edit', name: 'app_movie_edit')]
    public function save(Request $request, ?Movie $movie, EntityManagerInterface $manager): Response
    {
        if ($movie) {
            $this->denyAccessUnlessGranted(MovieVoter::EDIT, $movie);
        }
        $movie ??= new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$movie->getId() && ($user = $this->getUser()) instanceof User) {
                $movie->setCreatedBy($user);
            }
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('app_movie_show', ['id' => $movie->getId()]);
        }

        return $this->render('movie/save.html.twig', [
            'form' => $form
        ]);
    }

    public function decades(): Response
    {
        $decades = [
            '1980',
            '1990',
            '2010',
        ];

        return $this->render('includes/_decades.html.twig', [
            'decades' => $decades,
        ])->setMaxAge(3600);
    }
}
