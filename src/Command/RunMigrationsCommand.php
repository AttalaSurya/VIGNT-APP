<?php

namespace Vignt\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunMigrationsCommand extends Command
{
    protected static $defaultName = 'migration:run';

    protected function configure()
    {
        $this->setDescription('Run all pending migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = glob('migrations/*.vigmgrt.php');

        if (empty($files)) {
            $output->writeln("No migration files found.");
            return Command::SUCCESS;
        }

        $files = glob('migrations/*.vigmgrt.php');
        $migrated = $this->getMigratedMigrations();

        $toRun = array_diff($files, $migrated);
        if (empty($toRun)) {
            $output->writeln("No migrations to run.");
            return Command::SUCCESS;
        }

        foreach ($toRun as $file) {
            try {
                $output->writeln("Processing file: $file");
                require_once $file;
    
                $className = $this->getClassName($file);
                if (!class_exists($className)) {
                    $output->writeln("Class $className not found in file $file");
                    continue;
                }
    
                $migration = new $className();
    
                $migration->up();
                $this->markAsMigrated($file);
    
                $output->writeln("Migration run: $file");
            } catch (\Exception $e) {
                $output->writeln("error when processing file: $file");
                $msg = $e->getMessage();
                $output->writeln("error : $msg");
                die();
            }
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

    private function markAsMigrated($file)
    {
        file_put_contents('.migrated', $file . PHP_EOL, FILE_APPEND);
    }

    private function getMigratedMigrations() {
        if (!file_exists('.migrated')) {
            return [];
        }
        return file('.migrated', FILE_IGNORE_NEW_LINES);
    }
}
