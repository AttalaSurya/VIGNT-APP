<?php

namespace Vignt\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListMigrationsCommand extends Command
{
    protected static $defaultName = 'migration:list';

    protected function configure()
    {
        $this->setDescription('List all migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = glob('migrations/*.vigmgrt.php');
        $migrated = $this->getMigratedMigrations();
        $toRun = array_diff($files, $migrated);

        $list = "Migrated:\n";
        foreach ($migrated as $migration) {
            $list .= basename($migration) . "\n";
        }
        $list .= "\nTo Migrate:\n";
        foreach ($toRun as $migration) {
            $list .= basename($migration) . "\n";
        }

        file_put_contents('list_migration.txt', $list);
        $output->writeln("Migration list saved to list_migration.txt");

        return Command::SUCCESS;
    }

    private function getMigratedMigrations() {
        if (!file_exists('.migrated')) {
            return [];
        }
        return file('.migrated', FILE_IGNORE_NEW_LINES);
    }
}
