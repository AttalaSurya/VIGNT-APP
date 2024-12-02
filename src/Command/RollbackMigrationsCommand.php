<?php

namespace Vignt\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PDO;


class RollbackMigrationsCommand extends Command
{
    protected static $defaultName = 'migration:rollback';

    protected function configure()
    {
        $this
            ->setDescription('Rollback migrations by step')
            ->addArgument('steps', InputArgument::REQUIRED, 'Number of steps to rollback');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $steps = (int) $input->getArgument('steps');
        $migrations = $this->getMigratedMigrations();
        
        if ($steps <= 0 || $steps > count($migrations)) {
            $output->writeln("Invalid step count.");
            return Command::FAILURE;
        }

        $toRollback = array_splice($migrations, -$steps);
        foreach ($toRollback as $migration) {
            require_once $migration;
            $className = $this->getClassName($migration);
            if (!class_exists($className)) continue;

            $migrationInstance = new $className();
            $migrationInstance->down();
            $this->unmarkAsMigrated($migration);

            $output->writeln("Rolled back migration: $migration");
        }

        return Command::SUCCESS;
    }

    private function getClassName($file)
    {
        $filename = basename($file, '.vigmgrt.php');
        $parts = explode('_', $filename);
        $className = 'Vignt';
        foreach ($parts as $part) {
            $className .= ucfirst($part);
        }
        return $className;
    }

    private function getMigratedMigrations() {
        if (!file_exists('.migrated')) {
            return [];
        }
        return file('.migrated', FILE_IGNORE_NEW_LINES);
    }

    private function unmarkAsMigrated($file) {
        $migrated = file('.migrated', FILE_IGNORE_NEW_LINES);
        $migrated = array_diff($migrated, [$file]);
        $migrated[] = '';
        file_put_contents('.migrated', implode(PHP_EOL, $migrated));
    }
}
