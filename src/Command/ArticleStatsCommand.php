<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ArticleStatsCommand extends Command
{
    protected static $defaultName = 'article:stats';


    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Return some article stats.')
            ->addArgument('slug', InputArgument::REQUIRED, 'The slug of an article.')
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'The type of presenting the answer', 'text');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $slug = $input->getArgument('slug');

        $data = [
            'slug' => $slug,
            'likes' => rand(10, 100),
        ];

        switch ($input->getOption('format')) {
            case 'text':
                $result = array_map(function ($k, $v) {
                    return [$k, $v];
                }, array_keys($data), $data);
                $io->table(['Name', 'Value'], $result);
                break;
            case 'json':
                $io->writeln(json_encode($data));
                break;
            default:
                throw new \Exception('What format do you like to use?');
        }
    }
}
