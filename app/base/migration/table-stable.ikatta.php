<?php

class TableStable {
    private $columns = [];
    private $indexes = [];
    private $foreignKeys = [];
    private $alterations = [];
    private $nullableColumns = [];

    public function integer($name) {
        $this->columns[$name] = 'INT';
        return $this;
    }
    
    public function bigInteger($name) {
        $this->columns[$name] = 'BIGINT';
        return $this;
    }
    
    public function tinyInteger($name) {
        $this->columns[$name] = 'TINYINT';
        return $this;
    }
    
    public function smallInteger($name) {
        $this->columns[$name] = 'SMALLINT';
        return $this;
    }
    
    public function mediumInteger($name) {
        $this->columns[$name] = 'MEDIUMINT';
        return $this;
    }
    
    public function string($name, $length = 255) {
        $this->columns[$name] = "VARCHAR($length)";
        return $this;
    }
    
    public function text($name) {
        $this->columns[$name] = 'TEXT';
        return $this;
    }
    
    public function mediumText($name) {
        $this->columns[$name] = 'MEDIUMTEXT';
        return $this;
    }
    
    public function longText($name) {
        $this->columns[$name] = 'LONGTEXT';
        return $this;
    }
    
    public function blob($name) {
        $this->columns[$name] = 'BLOB';
        return $this;
    }
    
    public function mediumBlob($name) {
        $this->columns[$name] = 'MEDIUMBLOB';
        return $this;
    }
    
    public function longBlob($name) {
        $this->columns[$name] = 'LONGBLOB';
        return $this;
    }
    
    public function decimal($name, $precision = 8, $scale = 2) {
        $this->columns[$name] = "DECIMAL($precision, $scale)";
        return $this;
    }
    
    public function float($name, $precision = 8, $scale = 2) {
        $this->columns[$name] = "FLOAT($precision, $scale)";
        return $this;
    }
    
    public function double($name, $precision = 8, $scale = 2) {
        $this->columns[$name] = "DOUBLE($precision, $scale)";
        return $this;
    }
    
    public function date($name) {
        $this->columns[$name] = 'DATE';
        return $this;
    }
    
    public function time($name) {
        $this->columns[$name] = 'TIME';
        return $this;
    }
    
    public function dateTime($name) {
        $this->columns[$name] = 'DATETIME';
        return $this;
    }
    
    public function timestamp($name) {
        $this->columns[$name] = 'TIMESTAMP';
        return $this;
    }

    public function bigIncrements($name) {
        $this->columns[$name] = 'BIGINT AUTO_INCREMENT PRIMARY KEY';
        return $this;
    }

    public function nullable($columns, $nullable = true) {
        if (is_string($columns)) {
            $columns = [$columns];
        }

        foreach ($columns as $name) {
            if (isset($this->columns[$name])) {
                if ($nullable) {
                    if (strpos($this->columns[$name], 'NULL') === false) {
                        $this->columns[$name] = preg_replace('/\s*NOT NULL\s*/i', '', $this->columns[$name]) . ' NULL';
                    }
                } else {
                    if (strpos($this->columns[$name], 'NOT NULL') === false) {
                        $this->columns[$name] = preg_replace('/\s*NULL\s*/i', '', $this->columns[$name]) . ' NOT NULL';
                    }
                }
            } else {
                throw new Exception("Column $name does not exist.");
            }
        }
        return $this;
    }

    // Indexes
    public function index($columns, $name = null) {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $indexName = $name ? $name : 'idx_' . implode('_', (array)$columns);
        $this->indexes[] = "INDEX $indexName ($columns)";
        return $this;
    }
    
    public function uniqueIndex($columns, $name = null) {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $indexName = $name ? $name : 'uniq_' . implode('_', (array)$columns);
        $this->indexes[] = "UNIQUE INDEX $indexName ($columns)";
        return $this;
    }
    
    public function fullTextIndex($columns, $name = null) {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $indexName = $name ? $name : 'fulltext_' . implode('_', (array)$columns);
        $this->indexes[] = "FULLTEXT $indexName ($columns)";
        return $this;
    }

    public function foreign($column, $referencedTable, $referencedColumn = 'id', $name = null) {
        $foreignKeyName = $name ? $name : 'fk_' . $column;
        $this->foreignKeys[] = "FOREIGN KEY ($column) REFERENCES $referencedTable($referencedColumn) ON DELETE CASCADE ON UPDATE CASCADE";
        return $this;
    }
    
    public function timestamps() {
        $this->columns['created_at'] = 'DATETIME DEFAULT CURRENT_TIMESTAMP';
        $this->columns['updated_at'] = 'DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';
        return $this;
    }

    public function addColumn($name, $type) {
        $this->alterations[] = "ADD COLUMN $name $type";
        return $this;
    }

    public function dropColumn($name) {
        $this->alterations[] = "DROP COLUMN $name";
        return $this;
    }

    public function modifyColumn($name, $type) {
        $this->alterations[] = "MODIFY COLUMN $name $type";
        return $this;
    }

    public function dropIndex($name) {
        $this->alterations[] = "DROP INDEX $name";
        return $this;
    }

    public function dropForeign($name) {
        $this->alterations[] = "DROP FOREIGN KEY $name";
        return $this;
    }

    public function getColumns() {
        return $this->columns;
    }
    
    public function getIndexes() {
        return $this->indexes;
    }
    
    public function getForeignKeys() {
        return $this->foreignKeys;
    }

    public function getAlterations() {
        return $this->alterations;
    }
}
