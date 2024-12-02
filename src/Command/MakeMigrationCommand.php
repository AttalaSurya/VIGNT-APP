<?php

namespace Vignt\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMigrationCommand extends Command
{
    protected static $defaultName = 'migration:make';

    protected function configure()
    {
        $this
            ->setDescription('Create a new migration file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the migration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $timestamp = date('YmdHis');
        $filename = 'migrations/' . $timestamp . '_' . $name . '.vigmgrt.php';
        
        if (!file_exists('migrations')) {
            mkdir('migrations', 0777, true);
        }

        $class = $this->getClassName($name);

        $content = "<?php\n\n";
        $content .= "class " . ucfirst('Vignt'.$timestamp.$class) . " extends BaseMigration {\n";
        $content .= "    public function up() {\n";
        $content .= "        // Write your up migration logic here\n";
        $content .= "    }\n\n";
        $content .= "    public function down() {\n";
        $content .= "        // Write your down migration logic here\n";
        $content .= "    }\n";
        $content .= "}\n";
        
        file_put_contents($filename, $content);
        $output->writeln("Migration created: $filename");

        return Command::SUCCESS;
    }

    private function getClassName($file)
    {
        $parts = explode('_', $file);
        $className = '';
        foreach ($parts as $part) {
            $className .= ucfirst($part);
        }
        return $className;
    }
}
