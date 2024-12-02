<?php

namespace Vignt\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Vignt\Server;

class StartCommand extends Command
{
    protected static $defaultName = 'start';

    protected function configure()
    {
        $this
            ->setDescription('Start the PHP built-in server.')
            ->addOption(
                'host',
                null,
                InputOption::VALUE_REQUIRED,
                'The host to bind to',
                '127.0.0.1'
            )
            ->addOption(
                'port',
                null,
                InputOption::VALUE_REQUIRED,
                'The port to bind to',
                '8000'
            )
            ->addOption(
                'docroot',
                null,
                InputOption::VALUE_REQUIRED,
                'The document root directory',
                __DIR__ . '/../../vigntz'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getOption('host');
        $port = $input->getOption('port');
        $docroot = $input->getOption('docroot');

        $output->writeln("Starting server at http://$host:$port $docroot");
        
        $server = new Server($host, $port, $docroot);
        $server->start();
        
        return Command::SUCCESS;
    }
}
