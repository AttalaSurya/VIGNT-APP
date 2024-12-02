<?php

namespace Vignt\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckMigrationsCommand extends Command
{
    protected static $defaultName = 'migration:check';

    protected function configure()
    {
        $this->setDescription('Check for unrun migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = glob('migrations/*.vigmgrt.php');
        $migrated = $this->getMigratedMigrations();

        $toRun = array_diff($files, $migrated);
        if (empty($toRun)) {
            $output->writeln("No migrations to run.");
            return Command::SUCCESS;
        }

        $output->writeln("Migrations to run:");
        foreach ($toRun as $migration) {
            $output->writeln(basename($migration));
        }

        return Command::SUCCESS;
    }

    private function getMigratedMigrations() {
        if (!file_exists('.migrated')) {
            return [];
        }
        return file('.migrated', FILE_IGNORE_NEW_LINES);
    }
}
