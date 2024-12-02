<?php

namespace Vignt;

class Server
{
    private $host;
    private $port;
    private $docroot;

    public function __construct($host, $port, $docroot)
    {
        $this->host = $host;
        $this->port = $port;
        $this->docroot = $docroot;
    }

    public function start()
    {
        $command = "php -S {$this->host}:{$this->port} -t {$this->docroot}";
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            echo "Failed to start the server.\n";
            return;
        }

        echo "Server started successfully.\n";
    }
}
