<?php

class Table
{
    private $columns = [];
    private $indexes = [];
    private $foreignKeys = [];
    private $alterations = [];
    private $columnProperties = [];

    public function __construct($data)
    {
        $this->currentSchema = $data;
    }

    private function getColumnDetails($name)
    {
        foreach ($this->currentSchema as $column) {
            if ($column['Field'] === $name) {
                return $column;
            }
        }
        return null;
    }

    public function setCurrentSchema($tableName)
    {
        $this->fetchCurrentSchema($tableName);
    }

    public function integer($name)
    {
        $this->columns[$name] = 'INT';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function bigInteger($name)
    {
        $this->columns[$name] = 'BIGINT';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function tinyInteger($name)
    {
        $this->columns[$name] = 'TINYINT';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function smallInteger($name)
    {
        $this->columns[$name] = 'SMALLINT';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function mediumInteger($name)
    {
        $this->columns[$name] = 'MEDIUMINT';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function string($name, $length = 255)
    {
        $this->columns[$name] = "VARCHAR($length)";
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function text($name)
    {
        $this->columns[$name] = 'TEXT';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function mediumText($name)
    {
        $this->columns[$name] = 'MEDIUMTEXT';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function longText($name)
    {
        $this->columns[$name] = 'LONGTEXT';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function blob($name)
    {
        $this->columns[$name] = 'BLOB';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function mediumBlob($name)
    {
        $this->columns[$name] = 'MEDIUMBLOB';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function longBlob($name)
    {
        $this->columns[$name] = 'LONGBLOB';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function decimal($name, $precision = 8, $scale = 2)
    {
        $this->columns[$name] = "DECIMAL($precision, $scale)";
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function float($name, $precision = 8, $scale = 2)
    {
        $this->columns[$name] = "FLOAT($precision, $scale)";
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function double($name, $precision = 8, $scale = 2)
    {
        $this->columns[$name] = "DOUBLE($precision, $scale)";
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function date($name)
    {
        $this->columns[$name] = 'DATE';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function time($name)
    {
        $this->columns[$name] = 'TIME';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function dateTime($name)
    {
        $this->columns[$name] = 'DATETIME';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function timestamp($name)
    {
        $this->columns[$name] = 'TIMESTAMP';
        $this->columnProperties[$name] = 'NOT NULL';
        return $this;
    }

    public function bigIncrements($name)
    {
        $this->columns[$name] = 'BIGINT AUTO_INCREMENT PRIMARY KEY';
        $this->columnProperties[$name] = '';
        return $this;
    }

    public function nullable($columns, $nullable = true)
    {
        if (is_string($columns)) {
            $columns = [$columns];
        }

        foreach ($columns as $name) {
            $currentColumn = $this->getColumnDetails($name);
            if (isset($this->columns[$name])) {
                $currentColumn2 = $this->columns[$name];
            } else {
                $currentColumn2 = '';
            }

            if (!$currentColumn && $currentColumn2 != null && $this->currentSchema) {
                if ($nullable) {
                    $property = "NULL";
                } else {
                    $property = "NOT NULL";
                }
                $this->alterations = array_unique($this->alterations);
                $aazz = $this->alterations;

                foreach ($aazz as $iterate) {
                    $temp = strpos($iterate, "COLUMN $name");
                    if ($temp) {
                        $temp_arr = explode(' ', $iterate);
                        if (count($temp_arr) > 0) {
                            if (isset($temp_itt)) {
                                foreach ($temp_itt as $check) {
                                    if ($check == $iterate) {
                                        $key = array_search($iterate, $temp_itt);
                                        $temp_itt[$key] = "$temp_arr[0] $temp_arr[1] $temp_arr[2] " . $temp_arr[3] . ' ' . $property;
                                        break;
                                    }
                                }
                            }
                            $temp_itt[] = "$temp_arr[0] $temp_arr[1] $temp_arr[2] " . $temp_arr[3] . ' ' . $property;
                            $temp_itt = array_unique($temp_itt);
                            // var_dump($temp_itt);
                        }
                    } else {
                        if (isset($temp_itt)) {
                            foreach ($temp_itt as $iteration) {
                                $temp = strpos($iteration, "COLUMN $name");
                            }

                            if (!$temp) {
                                $temp_itt[] = $iterate;
                            }
                        }
                    }
                }
                $temp_itt = array_unique($temp_itt);
                $this->alterations = $temp_itt;
                // var_dump(3, $this->alterations);
            }

            if ($currentColumn != null && !$currentColumn2 && $this->currentSchema) {
                if ($nullable) {
                    $property = "NULL";
                } else {
                    $property = "NOT NULL";
                }

                $this->alterations = array_unique($this->alterations);
                $aazz = $this->alterations;
                foreach ($aazz as $iterate) {
                    $temp = strpos($iterate, "COLUMN $name");
                    if ($temp) {
                        $temp_arr = explode(' ', $iterate);
                        if (count($temp_arr) > 3) {
                            $temp_itt[] = "$temp_arr[0] $temp_arr[1] $temp_arr[2] " . $temp_arr[3] . ' ' . $property;
                        }
                    } else {
                        $temp_itt[] = $iterate;
                    }
                }
                $this->alterations = $temp_itt;

            } else if (isset($this->columns[$name])) {
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

    public function index($columns, $name = null)
    {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $indexName = $name ? $name : 'idx_' . implode('_', (array) $columns);
        $this->indexes[] = "INDEX $indexName ($columns)";
        return $this;
    }

    public function uniqueIndex($columns, $name = null)
    {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $indexName = $name ? $name : 'uniq_' . implode('_', (array) $columns);
        $this->indexes[] = "UNIQUE INDEX $indexName ($columns)";
        return $this;
    }

    public function fullTextIndex($columns, $name = null)
    {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $indexName = $name ? $name : 'fulltext_' . implode('_', (array) $columns);
        $this->indexes[] = "FULLTEXT $indexName ($columns)";
        return $this;
    }

    public function foreign($column, $referencedTable, $referencedColumn = 'id', $name = null)
    {
        $foreignKeyName = $name ? $name : 'fk_' . $column;
        $this->foreignKeys[] = "FOREIGN KEY ($column) REFERENCES $referencedTable($referencedColumn) ON DELETE CASCADE ON UPDATE CASCADE";
        return $this;
    }

    public function timestamps()
    {
        $this->columns['created_at'] = 'DATETIME DEFAULT CURRENT_TIMESTAMP';
        $this->columns['updated_at'] = 'DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';
        return $this;
    }

    public function modifyColumn($name, $type)
    {
        if (empty($this->currentSchema)) {
            throw new Exception("Current schema is not set.");
        }

        $currentColumn = $this->getColumnDetails($name);

        if ($currentColumn) {
            $currentType = $currentColumn['Type'];
            $currentNull = $currentColumn['Null'] === 'YES' ? 'NULL' : 'NOT NULL';
            $newType = $type;
            $property = isset($this->columnProperties[$name]) ? $this->columnProperties[$name] : $currentNull;

            if ($currentType !== $newType || $currentNull !== $property) {
                $this->alterations[] = "MODIFY COLUMN $name $newType $property";
            }
        } else {
            throw new Exception("Column $name does not exist.");
        }
        return $this;
    }

    public function addColumn($name, $type)
    {

        $this->columns[$name] = $type;
        $this->columnProperties[$name] = 'NOT NULL';
        $this->alterations[] = "ADD COLUMN $name $type";
        return $this;
    }

    public function dropColumn($name)
    {
        $this->alterations[] = "DROP COLUMN $name";
        return $this;
    }

    // public function modifyColumn($name, $type)
    // {
    //     if (isset($this->columns[$name])) {
    //         $property = isset($this->columnProperties[$name]) ? $this->columnProperties[$name] : 'NOT NULL';
    //         $this->alterations[] = "MODIFY COLUMN $name $type $property";
    //     } else {
    //         throw new Exception("Column $name does not exist.");
    //     }
    //     return $this;
    // }

    public function dropIndex($name)
    {
        $this->alterations[] = "DROP INDEX $name";
        return $this;
    }

    public function dropForeign($name)
    {
        $this->alterations[] = "DROP FOREIGN KEY $name";
        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getIndexes()
    {
        return $this->indexes;
    }

    public function getForeignKeys()
    {
        return $this->foreignKeys;
    }

    public function getAlterations()
    {
        return $this->alterations;
    }
}
