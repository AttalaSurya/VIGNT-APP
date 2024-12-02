<?php

class VigntDB
{
    private static $pdo;
    private static $config;
    private static $table;
    private static $whereClauses = [];
    private static $parameters = [];
    private static $rawSql;
    private static $rawParameters = [];
    private static $isWhereSet = false;
    private static $joinClauses = [];
    private static $selectedColumns = ['*'];
    private static $operationType = 'select';
    private static $distinct = false;
    private static $groupBy = [];
    private static $orderBy = [];

    private static function resetState()
    {
        self::$table = null;
        self::$whereClauses = [];
        self::$parameters = [];
        self::$rawSql = null;
        self::$rawParameters = [];
        self::$isWhereSet = false;
        self::$joinClauses = [];
        self::$selectedColumns = ['*'];
        self::$operationType = 'select';
        self::$distinct = false;
        self::$groupBy = [];
        self::$orderBy = [];
    }

    private static function generateUniquePlaceholder($baseName)
    {
        static $counter = 0;
        return ":{$baseName}_" . $counter++;
    }

    public static function database($configName)
    {
        $config = self::loadConfig($configName);

        if (!isset($config['dsn'], $config['username'], $config['password'])) {
            throw new Exception("Invalid configuration for '{$configName}'.");
        }

        self::resetState();

        self::$pdo = new PDO($config['dsn'], $config['username'], $config['password']);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return new self();
    }

    private static function loadConfig($configName)
    {
        $config = baseconfig('database.' . $configName);

        if ($config === null) {
            throw new Exception("Configuration '{$configName}' not found.");
        }

        return $config;
    }

    public function table($table)
    {
        self::$table = $table;
        return $this;
    }

    public function where($column, $operator, $value)
    {
        $placeholder = self::generateUniquePlaceholder($column);
        if (self::$isWhereSet) {
            self::$whereClauses[] = "AND {$column} {$operator} {$placeholder}";
        } else {
            self::$whereClauses[] = "{$column} {$operator} {$placeholder}";
            self::$isWhereSet = true;
        }
        self::$parameters[$placeholder] = $value;
        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        $placeholder = self::generateUniquePlaceholder($column);
        if (self::$isWhereSet) {
            self::$whereClauses[] = "OR {$column} {$operator} {$placeholder}";
        } else {
            self::$whereClauses[] = "{$column} {$operator} {$placeholder}";
            self::$isWhereSet = true;
        }
        self::$parameters[$placeholder] = $value;
        return $this;
    }

    public function whereIn($column, array $values)
    {
        $placeholders = implode(', ', array_map(function ($index) use ($column) {
            return self::generateUniquePlaceholder($column . "_{$index}");
        }, array_keys($values)));

        if (self::$isWhereSet) {
            self::$whereClauses[] = "AND {$column} IN ({$placeholders})";
        } else {
            self::$whereClauses[] = "{$column} IN ({$placeholders})";
            self::$isWhereSet = true;
        }

        foreach ($values as $index => $value) {
            self::$parameters[self::generateUniquePlaceholder($column . "_{$index}")] = $value;
        }
        return $this;
    }

    public function whereNotIn($column, array $values)
    {
        $placeholders = implode(', ', array_map(function ($index) use ($column) {
            return self::generateUniquePlaceholder($column . "_{$index}");
        }, array_keys($values)));

        if (self::$isWhereSet) {
            self::$whereClauses[] = "AND {$column} NOT IN ({$placeholders})";
        } else {
            self::$whereClauses[] = "{$column} NOT IN ({$placeholders})";
            self::$isWhereSet = true;
        }

        foreach ($values as $index => $value) {
            self::$parameters[self::generateUniquePlaceholder($column . "_{$index}")] = $value;
        }
        return $this;
    }

    public function whereDate($column, $operator, $date)
    {
        $formattedDate = date('Y-m-d', strtotime($date));
        $placeholder = ":{$column}";
        if (self::$isWhereSet) {
            self::$whereClauses[] = "AND {$column} {$operator} {$placeholder}";
        } else {
            self::$whereClauses[] = "{$column} {$operator} {$placeholder}";
            self::$isWhereSet = true;
        }
        self::$parameters[$placeholder] = $formattedDate;
        return $this;
    }

    public static function whereRaw($condition, array $params = [])
    {
        if (self::$isWhereSet) {
            self::$whereClauses[] = "AND ({$condition})";
        } else {
            self::$whereClauses[] = "({$condition})";
            self::$isWhereSet = true;
        }
    
        foreach ($params as $key => $value) {
            $paramName = ":{$key}";
            self::$parameters[$paramName] = $value;
            $condition = str_replace($paramName, ":{$key}", $condition);
        }
    
        return new self();
    }
    

    public static function orWhereRaw($condition, array $params = [])
    {
        if (self::$isWhereSet) {
            self::$whereClauses[] = "OR ({$condition})";
        } else {
            self::$whereClauses[] = "({$condition})";
            self::$isWhereSet = true;
        }
    
        foreach ($params as $key => $value) {
            $paramName = ":{$key}";
            self::$parameters[$paramName] = $value;
            $condition = str_replace($paramName, ":{$key}", $condition);
        }
    
        return new self();
    }

    public function join($table, $first, $operator, $second, $type = 'INNER')
    {
        self::$joinClauses[] = "{$type} JOIN {$table} ON {$first} {$operator} {$second}";
        return $this;
    }

    public function select(array $columns)
    {
        self::$selectedColumns = $columns;
        self::$operationType = 'select';
        return $this;
    }

    public function distinct()
    {
        self::$distinct = true;
        return $this;
    }

    public function groupBy($columns)
    {
        self::$groupBy = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        self::$orderBy[] = "{$column} {$direction}";
        return $this;
    }

    public function count()
    {
        if (self::$rawSql) {
            $sql = "SELECT COUNT(*) as count FROM (" . self::$rawSql . ") as vigntsubqcounting";
            $params = self::$rawParameters;
            self::resetRawState();
        } else {
            if (empty(self::$table)) {
                throw new Exception("Table name must be specified. Current value: '" . self::$table . "'");
            }
            $sql = "SELECT COUNT(*) as count" . self::buildFromClause() . self::buildJoinClause() . self::buildWhereClause();
            $params = self::$parameters;
            self::resetConditions();
        }
        $stmt = self::runQuery($sql, $params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    private static function buildFromClause()
    {
        if (empty(self::$table)) {
            throw new Exception("Table name must be specified. Current value: '" . self::$table . "'");
        }
        return " FROM " . self::$table;
    }

    private static function buildWhereClause()
    {
        if (empty(self::$whereClauses)) {
            return '';
        }

        $whereClause = ' WHERE ' . implode(' ', self::$whereClauses);
        return $whereClause;
    }

    private static function buildJoinClause()
    {
        if (empty(self::$joinClauses)) {
            return '';
        }
        return ' ' . implode(' ', self::$joinClauses);
    }

    private static function buildGroupByClause()
    {
        if (empty(self::$groupBy)) {
            return '';
        }

        return ' GROUP BY ' . implode(', ', self::$groupBy);
    }

    private static function buildOrderByClause()
    {
        if (empty(self::$orderBy)) {
            return '';
        }

        return ' ORDER BY ' . implode(', ', self::$orderBy);
    }

    private static function runQuery($sql, $params)
    {
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function param($params)
    {
        self::$rawParameters = $params;
        return new self();
    }

    public function raw($sql)
    {
        self::$rawSql = $sql;
        self::$rawParameters = [];
        self::resetConditions();
        return new self();
    }

    public function getAll()
    {
        $sql = '';
        $params = [];
    
        
        if (self::$rawSql) {
            $sql = self::$rawSql;
            $params = self::$rawParameters;
            self::resetRawState(); 
        } else {
            $sql = "SELECT " . (self::$distinct ? 'DISTINCT ' : '') . implode(', ', self::$selectedColumns) 
                . " FROM " . self::$table 
                . self::buildJoinClause() 
                . self::buildWhereClause() 
                . self::buildGroupByClause() 
                . self::buildOrderByClause();
                $params = self::$parameters;
            }
            
            $stmt = self::runQuery($sql, $params);

            self::resetConditions();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne()
    {
        
        if (self::$rawSql) {
            $sql = self::$rawSql . " LIMIT 1";
            $params = self::$rawParameters;
            self::resetRawState();
        } else {
            $sql = "SELECT " . (self::$distinct ? 'DISTINCT ' : '') . implode(', ', self::$selectedColumns) . " FROM " . self::$table . self::buildJoinClause() . self::buildWhereClause() . self::buildGroupByClause() . self::buildOrderByClause() . " LIMIT 1";
            $params = self::$parameters;
        }
        
        $stmt = self::runQuery($sql, $params);
        self::resetConditions();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLast()
    {
        $sql = "SELECT " . (self::$distinct ? 'DISTINCT ' : '') . implode(', ', self::$selectedColumns) . " FROM " . self::$table . self::buildOrderByClause() . " ORDER BY id DESC LIMIT 1";
        $stmt = self::runQuery($sql, self::$parameters);
        self::resetConditions();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert(array $data)
    {   
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(function ($key) {
            return ":{$key}";
        }, array_keys($data)));
        $sql = "INSERT INTO " . self::$table . " ({$columns}) VALUES ({$placeholders})";
        
        self::runQuery($sql, $data);
        self::resetConditions();
        return self::$pdo->lastInsertId();
    }

    public function update(array $data)
    {
        $setClauses = implode(', ', array_map(function ($key) {
            return "{$key} = :{$key}";
        }, array_keys($data)));
        
        $sql = "UPDATE " . self::$table . " SET {$setClauses}" . self::buildWhereClause();
        
        $params = array_merge($data, self::$parameters);
        $stmt   = self::runQuery($sql, $params);

        self::resetConditions();

        return $stmt->rowCount();
    }

    public function delete()
    {
        $sql = "DELETE FROM " . self::$table . self::buildWhereClause();
        $stmt = self::runQuery($sql, self::$parameters);
        self::resetConditions();
        return $stmt->rowCount();
    }

    private static function resetConditions()
    {
        self::$whereClauses         = [];
        self::$parameters           = [];
        self::$isWhereSet           = false;
        self::$joinClauses          = [];
        self::$selectedColumns      = ['*'];
        self::$operationType        = 'select';
        self::$distinct             = false;
        self::$groupBy              = [];
        self::$orderBy              = [];
    }

    private static function resetRawState()
    {
        self::$rawSql           = null;
        self::$rawParameters    = [];
    }

    public function sql()
    {
        $sql = '';

        switch (self::$operationType) {
            case 'select':
                $sql = "SELECT " . (self::$distinct ? 'DISTINCT ' : '') . implode(', ', self::$selectedColumns) . " FROM " . self::$table . self::buildJoinClause() . self::buildWhereClause() . self::buildGroupByClause() . self::buildOrderByClause();
                break;

            case 'insert':
                $columns = implode(', ', array_keys(self::$parameters));
                $placeholders = implode(', ', array_map(function ($key) {
                    return ":{$key}";
                }, array_keys(self::$parameters)));
                $sql = "INSERT INTO " . self::$table . " ({$columns}) VALUES ({$placeholders})";
                break;

            case 'update':
                $setClauses = implode(', ', array_map(function ($key) {
                    return "{$key} = :{$key}";
                }, array_keys(self::$parameters)));
                $sql = "UPDATE " . self::$table . " SET {$setClauses}" . self::buildWhereClause();
                break;

            case 'delete':
                $sql = "DELETE FROM " . self::$table . self::buildWhereClause();
                break;

            default:
                throw new Exception("Unsupported operation type.");
        }

        return $sql;
    }

    public function sqlwp()
    {
        $sql = '';

        switch (self::$operationType) {
            case 'select':
                $sql = "SELECT " . (self::$distinct ? 'DISTINCT ' : '') . implode(', ', self::$selectedColumns) . " FROM " . self::$table . self::buildJoinClause() . self::buildWhereClause() . self::buildGroupByClause() . self::buildOrderByClause();
                break;

            case 'insert':
                $columns = implode(', ', array_keys(self::$parameters));
                $placeholders = implode(', ', array_map(function ($key) {
                    return ":{$key}";
                }, array_keys(self::$parameters)));
                $sql = "INSERT INTO " . self::$table . " ({$columns}) VALUES ({$placeholders})";
                break;

            case 'update':
                $setClauses = implode(', ', array_map(function ($key) {
                    return "{$key} = :{$key}";
                }, array_keys(self::$parameters)));
                $sql = "UPDATE " . self::$table . " SET {$setClauses}" . self::buildWhereClause();
                break;

            case 'delete':
                $sql = "DELETE FROM " . self::$table . self::buildWhereClause();
                break;

            default:
                throw new Exception("Unsupported operation type.");
        }

        foreach (static::$parameters as $param => $value) {
            $value = is_string($value) ? "'" . addslashes($value) . "'" : (is_null($value) ? 'NULL' : $value);
            $sql = str_replace($param, $value, $sql);
        }

        return $sql;
    }
}
