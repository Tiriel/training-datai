<?php

namespace App\Command;

use App\Entity\Movie;
use App\Movie\Provider\MovieProvider;
use App\Movie\SearchTypeEnum;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsCommand(
    name: 'app:movie:find',
    description: 'Search a movie by title or IMDb ID',
)]
class MovieFindCommand extends Command
{
    private ?string $value = null;
    private ?SearchTypeEnum $type = null;
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly MovieRepository $repository,
        private readonly MovieProvider $provider,
    ){
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('value', InputArgument::OPTIONAL, 'The title or IMDb ID you are searching for')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->value = $input->getArgument('value');
        $this->io = new SymfonyStyle($input, $output);
        $this->provider->setIo($this->io);

        if ($this->value) {
            $this->type = $this->getType();
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (!$this->value) {
            $this->value = $this->io->ask('What is the title or IMDb ID you are searching for ?');
            $this->type = $this->getType();
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title(sprintf('You are searching for a movie with %s "%s"', $this->type->name, $this->value));

        if (
            $this->type === SearchTypeEnum::Id
            && ($movie = $this->repository->findOneBy(['imdbId' => $this->value]))
        ) {
            $this->io->note('Movie already in database!');
            $this->displayTable($movie);
            $this->io->success('Movie in database!');

            return Command::SUCCESS;
        }

        try {
            $movie = $this->provider->getMovie($this->type, $this->value);
        } catch (NotFoundHttpException) {
            $this->io->error('Movie not found!');

            return Command::FAILURE;
        }

        $this->displayTable($movie);
        $this->io->success('Movie in database!');

        return Command::SUCCESS;
    }

    private function getType(): SearchTypeEnum
    {
        return 0 !== preg_match('/tt\d{6,8}/i', $this->value) ? SearchTypeEnum::Id : SearchTypeEnum::Title;
    }

    private function displayTable(Movie $movie): void
    {
        $this->io->table(
            ['Id', 'IMDb Id', 'Title', 'Rated'],
            [[$movie->getId(), $movie->getImdbId(), $movie->getTitle(), $movie->getRated()]]
        );
    }
}
