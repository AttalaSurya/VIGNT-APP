<?php

namespace Vignt\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModelCommand extends Command
{
    protected static $defaultName = 'create:model';

    protected function configure()
    {
        $this
            ->setDescription('Create a new model file.')
            ->addArgument('modelName', InputArgument::REQUIRED, 'The name of the model to create.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modelName = $input->getArgument('modelName');
        $modelFile = "Ikatta/model/{$modelName}.ikatta.php";
        
        $tableName = strtolower($modelName);

        $modelTemplate = <<<PHP
<?php

class {$modelName} extends VigntModel
{
    protected static \$connection = 'default';
    protected static \$table = '{$tableName}';

    protected static \$guarded = [];

    // Add specific methods for this model here
}
PHP;

        if (file_exists($modelFile)) {
            $output->writeln("<error>Model '{$modelName}' already exists.</error>");
            return Command::FAILURE;
        }

        file_put_contents($modelFile, $modelTemplate);
        $output->writeln("<info>Model '{$modelName}' created successfully at '{$modelFile}'.</info>");
        return Command::SUCCESS;
    }
}
