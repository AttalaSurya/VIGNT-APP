<?php

class VigntMigrate {
    private $pdo;
    
    private function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function fetchCurrentSchema($tableName)
    {
        $query = "SHOW COLUMNS FROM $tableName";
        $stmt = $this->pdo->query($query);
        $this->currentSchema = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$this->currentSchema) {
            throw new Exception("Table $tableName not found.");
        }
    }

    
    public static function connection($connectionName) {
        $config = self::loadConfig();
        
        if (!isset($config['database'][$connectionName])) {
            throw new Exception("Connection '$connectionName' not found in config.");
        }

        $dbConfig = $config['database'][$connectionName];
        
        try {
            $pdo = new PDO($dbConfig['dsn'], $dbConfig['username'], $dbConfig['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return new self($pdo);
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    private static function loadConfig() {
        $configPath = '.baseconfig';
        if (!file_exists($configPath)) {
            throw new Exception("Configuration file not found.");
        }

        $configContent = file_get_contents($configPath);
        return json_decode($configContent, true);
    }
    
    public function create($tableName, $callback) {
        // $currentSchema = $this->getCurrentSchema($tableName);
        $currentSchema = [];
        $table = new Table($currentSchema);
        $callback($table);
        $this->createTable($tableName, $table);
    }

    private function createTable($tableName, $table) {
        $columns = $table->getColumns();
        $indexes = $table->getIndexes();
        $foreignKeys = $table->getForeignKeys();
    
        $columnsSql = implode(', ', array_map(
            function ($column, $definition) { return "$column $definition"; },
            array_keys($columns),
            $columns
        ));
    
        $indexesSql = implode(', ', $indexes);
    
        $foreignKeysSql = implode(', ', $foreignKeys);

        $sql = "CREATE TABLE $tableName ($columnsSql";
        
        if ($indexesSql) {
            $sql .= ", $indexesSql";
        }
        
        if ($foreignKeysSql) {
            $sql .= ", $foreignKeysSql";
        }
        
        $sql .= ")";
        $this->pdo->exec($sql);
    }
    
    public function dropIfExists($tableName) {
        $sql = "DROP TABLE IF EXISTS $tableName";
        $this->pdo->exec($sql);
    }
    
    public function alter($tableName, $callback) {
        // var_dump(1);
        // die();
        $currentSchema = $this->getCurrentSchema($tableName);
        $table = new Table($currentSchema);
        $callback($table);
        $alterStatements = $this->generateAlterStatements($table, $currentSchema);
        if ($alterStatements) {
            $sql = "ALTER TABLE $tableName $alterStatements";
            $this->pdo->exec($sql);
        }
    }

    private function getCurrentSchema($tableName) {
        $query = "SHOW COLUMNS FROM $tableName";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function generateAlterStatements(Table $table, array $currentSchema) {
        $alterations = $table->getAlterations();
        $columns = $table->getColumns();
        $currentColumns = [];
        
        foreach ($currentSchema as $column) {
            $currentColumns[$column['Field']] = [
                'Type' => $column['Type'],
                'Null' => $column['Null']
            ];
        }

        $alterStatements = [];
        foreach ($alterations as $alteration) {
            if (strpos($alteration, 'ADD COLUMN') === 0) {
                // ADD COLUMN should only add new columns
                $alterStatements[] = $alteration;
            } elseif (strpos($alteration, 'DROP COLUMN') === 0) {
                // DROP COLUMN should only drop existing columns
                $columnName = trim(str_replace('DROP COLUMN', '', $alteration));
                if (isset($currentColumns[$columnName])) {
                    $alterStatements[] = $alteration;
                }
            } elseif (strpos($alteration, 'MODIFY COLUMN') === 0) {
                // MODIFY COLUMN should compare with the current schema
                $parts = explode(' ', trim(str_replace('MODIFY COLUMN', '', $alteration)), 2);
                $columnName = $parts[0];
                $newDefinition = $parts[1];
                
                if (isset($currentColumns[$columnName])) {
                    $currentDefinition = $currentColumns[$columnName]['Type'];
                    $currentNullability = $currentColumns[$columnName]['Null'];
                    
                    // Only generate modify statement if there's a change
                    if ($newDefinition !== $currentDefinition || 
                        ($newDefinition !== 'NULL' && $currentNullability === 'YES') || 
                        ($newDefinition !== 'NOT NULL' && $currentNullability === 'NO')) {
                        $alterStatements[] = "MODIFY COLUMN $columnName $newDefinition";
                    }
                } else {
                    throw new Exception("Column $columnName does not exist in the current schema.");
                }
            }
        }
        
        return implode(', ', $alterStatements);
    }
}
