<?php

namespace App\Command;

use App\Entity\Movie;
use App\Movie\Search\Enum\SearchType;
use App\Movie\Search\Provider\MovieProvider;
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
    description: 'Add a short description for your command',
)]
class MovieFindCommand extends Command
{
    private ?string $value = null;
    private ?SearchType $type = null;
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly MovieProvider $provider,
        private readonly MovieRepository $movieRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('value', InputArgument::OPTIONAL, 'The value (title or IMDb ID) you are searching for.')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->provider->setIo($this->io);

        $this->value = $input->getArgument('value');
        if (null !== $this->value) {
            $this->getType();
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        while (null === $this->value) {
            $this->value = $this->io->ask('What is the title or IMDb ID you are searching for ?');
        }
        $this->getType();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title(sprintf('You are searching for a movie with %s "%s".', $this->type->value, $this->value));

        if ($this->type === SearchType::Id
            && (($movie = $this->movieRepository->findOneBy(['imdbId' => $this->value])) instanceof Movie)
        ) {
            $this->io->note('Movie already in database!');
            $this->displayTable($movie);

            return Command::SUCCESS;
        }

        try {
            $movie = $this->provider->getOne($this->value, $this->type);
        } catch (NotFoundHttpException) {
            $this->io->error('Movie not found!');

            return Command::FAILURE;
        }

        $this->displayTable($movie);

        return Command::SUCCESS;
    }

    private function getType(): void
    {
        $this->type = 0 !== preg_match('/tt\d{6,8}/i', $this->value) ? SearchType::Id : SearchType::Title;
    }

    private function displayTable(Movie $movie): void
    {
        $this->io->table(
            ['id', 'IMDb ID', 'Title', 'Rated'],
            [[$movie->getId(), $movie->getImdbId(), $movie->getTitle(), $movie->getRated()]]
        );

        $this->io->success('Movie in database!');
    }
}
