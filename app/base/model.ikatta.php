<?php

class VigntModel
{
    protected static $pdo;
    protected static $table;
    protected static $connection = 'default';
    protected static $whereClauses = [];
    protected static $parameters = [];
    protected static $isWhereSet = false;
    protected static $joinClauses = [];
    protected static $fillable = [];
    protected static $guarded = [];
    protected static $insertData = [];
    protected static $updateData = [];
    protected static $selectedColumns = ['*'];
    protected static $operationType = 'select';
    protected static $rawParameters = [];

    protected static $distinct = false;
    protected static $count = false;
    protected static $orderBy = [];
    protected static $groupBy = [];
    protected static $parameterCounter = 0;

    protected static function getConnection()
    {
        if (self::$pdo) {
            return self::$pdo;
        }

        $config = self::loadConfig(static::$connection);

        if (!isset($config['dsn'], $config['username'], $config['password'])) {
            throw new Exception("Invalid configuration for '" . static::$connection . "'.");
        }

        self::$pdo = new PDO($config['dsn'], $config['username'], $config['password']);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return self::$pdo;
    }

    private static function generateUniqueParameterName($baseName)
    {
        static $counter = 1;
        return $baseName . '_' . $counter++;
    }

    public static function count()
    {
        static::checkTable();
        static::$operationType = 'select';
        static::$count = true;
        static::$selectedColumns = ['COUNT(*) AS count'];

        return new static();
    }

    public static function orderBy($column, $direction = 'ASC')
    {
        static::$orderBy[] = "{$column} {$direction}";
        return new static();
    }

    public static function groupBy($column)
    {
        static::$groupBy[] = $column;
        return new static();
    }

    public static function distinct()
    {
        static::$distinct = true;
        return new static();
    }

    private static function buildJoinClause()
    {
        if (empty(static::$joinClauses)) {
            return '';
        }
        return ' ' . implode(' ', static::$joinClauses);
    }

    private static function buildWhereClause()
    {
        $whereClause = '';
        if (!empty(static::$whereClauses)) {
            $whereClause = ' WHERE ' . implode(' ', static::$whereClauses);
        }
        return $whereClause;
    }

    private static function buildOrderByClause()
    {
        if (empty(static::$orderBy)) {
            return '';
        }
        return ' ORDER BY ' . implode(', ', static::$orderBy);
    }

    private static function buildGroupByClause()
    {
        if (empty(static::$groupBy)) {
            return '';
        }
        return ' GROUP BY ' . implode(', ', static::$groupBy);
    }

    private static function buildDistinctClause()
    {
        return static::$distinct ? ' DISTINCT' : '';
    }

    private static function resetConditions()
    {
        static::$whereClauses = [];
        static::$parameters = [];
        static::$isWhereSet = false;
        static::$joinClauses = [];
        static::$insertData = [];
        static::$updateData = [];
        static::$selectedColumns = ['*'];
        static::$operationType = 'select';
        static::$distinct = false;
        static::$count = false;
        static::$orderBy = [];
        static::$groupBy = [];
    }

    public static function one()
    {
        static::checkTable();
        static::$operationType = 'select';

        $pdo = static::getConnection();

        $sql = "SELECT " . implode(', ', static::$selectedColumns)
        . " FROM " . static::$table
        . static::buildJoinClause()
        . static::buildWhereClause()
            . " LIMIT 1";
        $params = static::$parameters;

        $stmt = static::runQuery($sql, $params);
        static::resetConditions();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new VigntQuery($data) : null;
    }

    public static function last()
    {
        static::checkTable();
        static::$operationType = 'select';

        $pdo = static::getConnection();

        $sql = "SELECT " . implode(', ', static::$selectedColumns)
        . " FROM " . static::$table
        . static::buildJoinClause()
        . static::buildWhereClause()
            . " ORDER BY id DESC LIMIT 1";
        $params = static::$parameters;

        $stmt = static::runQuery($sql, $params);
        static::resetConditions();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new VigntQuery($data) : null;
    }

    public static function alls()
    {
        static::checkTable();
        static::$operationType = 'select';

        $pdo = static::getConnection();

        $sql = "SELECT " . implode(', ', static::$selectedColumns)
        . " FROM " . static::$table
        . static::buildJoinClause()
        . static::buildWhereClause();
        $params = static::$parameters;

        $stmt = static::runQuery($sql, $params);
        static::resetConditions();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($item) {
            return new VigntQuery($item);
        }, $data);
    }

    public static function param($params)
    {
        $params = static::$parameters;
        static::$rawParameters = $params;
        return new static();
    }

    public static function getOne()
    {
        static::checkTable();
        static::$operationType = 'select';

        $pdo = static::getConnection();

        $sql = "SELECT " . static::buildDistinctClause() . " " . implode(', ', static::$selectedColumns)
        . " FROM " . static::$table
        . static::buildJoinClause()
        . static::buildWhereClause()
        . static::buildGroupByClause()
        . static::buildOrderByClause()
            . " LIMIT 1";
        $params = static::$parameters;

        $stmt = static::runQuery($sql, $params);
        static::resetConditions();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getLast()
    {
        static::checkTable();
        static::$operationType = 'select';

        $pdo = static::getConnection();

        $sql = "SELECT " . static::buildDistinctClause() . " " . implode(', ', static::$selectedColumns)
        . " FROM " . static::$table
        . static::buildJoinClause()
        . static::buildWhereClause()
        . static::buildGroupByClause()
        . static::buildOrderByClause()
            . " LIMIT 1";
        $params = static::$parameters;

        $stmt = static::runQuery($sql, $params);
        static::resetConditions();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll()
    {
        static::checkTable();
        static::$operationType = 'select';

        $pdo = static::getConnection();

        $sql = "SELECT " . static::buildDistinctClause() . " " . implode(', ', static::$selectedColumns)
        . " FROM " . static::$table
        . static::buildJoinClause()
        . static::buildWhereClause()
        . static::buildGroupByClause()
        . static::buildOrderByClause();
        $params = static::$parameters;

        $stmt = static::runQuery($sql, $params);
        static::resetConditions();

        return static::$count ? $stmt->fetchColumn() : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insert(array $data)
    {
        static::checkTable();
        static::checkFillable($data);
        static::$operationType = 'insert';
        static::$insertData = $data;

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(function ($key) {
            return ":{$key}";
        }, array_keys($data)));

        $sql = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";
        static::runQuery($sql, $data);
        return static::getConnection()->lastInsertId();
    }

    public static function update(array $data)
    {
        static::checkTable();
        static::checkFillable($data);
        static::$operationType = 'update';
        static::$updateData = $data;

        $setClauses = implode(', ', array_map(function ($key) {
            return "{$key} = :{$key}";
        }, array_keys($data)));

        $sql = "UPDATE " . static::$table . " SET {$setClauses}" . static::buildWhereClause();
        static::runQuery($sql, $data);
        return static::getConnection()->lastInsertId();
    }

    public static function delete()
    {
        static::checkTable();
        static::$operationType = 'delete';

        $sql = "DELETE FROM " . static::$table . static::buildWhereClause();
        static::runQuery($sql, static::$parameters);
        static::resetConditions();
    }

    private static function loadConfig($configName)
    {
        $config = baseconfig('database.' . $configName);

        if ($config === null) {
            throw new Exception("Configuration '{$configName}' not found.");
        }

        return $config;
    }

    protected static function checkTable()
    {
        if (empty(static::$table)) {
            throw new Exception("Table name is not set.");
        }
    }

    protected static function checkFillable(array $data)
    {
        if (!empty(static::$fillable)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, static::$fillable)) {
                    throw new Exception("Field '{$key}' is not fillable.");
                }
            }
        }

        if (!empty(static::$guarded)) {
            foreach ($data as $key => $value) {
                if (in_array($key, static::$guarded)) {
                    throw new Exception("Field '{$key}' is guarded and cannot be updated.");
                }
            }
        }
    }

    public static function whereRaw($condition, array $params = [])
    {
        if (static::$isWhereSet) {
            static::$whereClauses[] = "AND ({$condition})";
        } else {
            static::$whereClauses[] = "({$condition})";
            static::$isWhereSet = true;
        }

        foreach ($params as $key => $value) {
            $paramName = ":{$key}";
            static::$parameters[$paramName] = $value;
            $condition = str_replace($paramName, ":{$key}", $condition);
        }

        return new static();
    }

    public static function orWhereRaw($condition, array $params = [])
    {
        if (static::$isWhereSet) {
            static::$whereClauses[] = "OR ({$condition})";
        } else {
            static::$whereClauses[] = "({$condition})";
            static::$isWhereSet = true;
        }

        foreach ($params as $key => $value) {
            $paramName = ":{$key}";
            static::$parameters[$paramName] = $value;
            $condition = str_replace($paramName, ":{$key}", $condition);
        }

        return new static();
    }

    public static function where($column, $operator, $value)
    {
        $paramName = static::generateUniqueParameterName($column);

        if (static::$isWhereSet) {
            static::$whereClauses[] = "AND {$column} {$operator} :{$paramName}";
        } else {
            static::$whereClauses[] = "{$column} {$operator} :{$paramName}";
            static::$isWhereSet = true;
        }
        static::$parameters[":{$paramName}"] = $value;
        return new static();
    }

    public static function orWhere($column, $operator, $value)
    {
        $paramName = static::generateUniqueParameterName($column);

        if (static::$isWhereSet) {
            static::$whereClauses[] = "OR {$column} {$operator} :{$paramName}";
        } else {
            static::$whereClauses[] = "{$column} {$operator} :{$paramName}";
            static::$isWhereSet = true;
        }
        static::$parameters[":{$paramName}"] = $value;
        return new static();
    }

    public static function whereNull($column)
    {
        if (static::$isWhereSet) {
            static::$whereClauses[] = "AND {$column} IS NULL";
        } else {
            static::$whereClauses[] = "{$column} IS NULL";
            static::$isWhereSet = true;
        }
        return new static();
    }

    public static function orWhereNull($column)
    {
        if (static::$isWhereSet) {
            static::$whereClauses[] = "OR {$column} IS NULL";
        } else {
            static::$whereClauses[] = "{$column} IS NULL";
            static::$isWhereSet = true;
        }
        return new static();
    }

    public static function whereIn($column, array $values)
    {
        $placeholders = implode(', ', array_map(function ($index) use ($column) {
            return ":{$column}_{$index}";
        }, array_keys($values)));

        if (static::$isWhereSet) {
            static::$whereClauses[] = "AND {$column} IN ({$placeholders})";
        } else {
            static::$whereClauses[] = "{$column} IN ({$placeholders})";
            static::$isWhereSet = true;
        }

        foreach ($values as $index => $value) {
            static::$parameters[":{$column}_{$index}"] = $value;
        }
        return new static();
    }

    public static function orWhereIn($column, array $values)
    {
        $placeholders = implode(', ', array_map(function ($index) use ($column) {
            return ":{$column}_{$index}";
        }, array_keys($values)));

        if (static::$isWhereSet) {
            static::$whereClauses[] = "OR {$column} IN ({$placeholders})";
        } else {
            static::$whereClauses[] = "{$column} IN ({$placeholders})";
            static::$isWhereSet = true;
        }

        foreach ($values as $index => $value) {
            static::$parameters[":{$column}_{$index}"] = $value;
        }
        return new static();
    }

    public static function whereNotIn($column, array $values)
    {
        $placeholders = implode(', ', array_map(function ($index) use ($column) {
            return ":{$column}_{$index}";
        }, array_keys($values)));

        if (static::$isWhereSet) {
            static::$whereClauses[] = "AND {$column} NOT IN ({$placeholders})";
        } else {
            static::$whereClauses[] = "{$column} NOT IN ({$placeholders})";
            static::$isWhereSet = true;
        }

        foreach ($values as $index => $value) {
            static::$parameters[":{$column}_{$index}"] = $value;
        }
        return new static();
    }

    public static function whereDate($column, $operator, $value)
    {
        $formattedValue = date('Y-m-d', strtotime($value));

        if (static::$isWhereSet) {
            static::$whereClauses[] = "AND DATE({$column}) {$operator} :{$column}";
        } else {
            static::$whereClauses[] = "DATE({$column}) {$operator} :{$column}";
            static::$isWhereSet = true;
        }
        static::$parameters[":{$column}"] = $formattedValue;
        return new static();
    }

    public static function join($table, $first, $operator, $second, $type = 'INNER')
    {
        static::$joinClauses[] = "{$type} JOIN {$table} ON {$first} {$operator} {$second}";
        return new static();
    }

    public static function select(array $columns)
    {
        static::$selectedColumns = $columns;
        static::$operationType = 'select';
        return new static();
    }

    public static function sql()
    {
        static::checkTable();

        switch (static::$operationType) {
            case 'select':
                $sql = "SELECT " . static::buildDistinctClause() . " " . implode(', ', static::$selectedColumns)
                . " FROM " . static::$table
                . static::buildJoinClause()
                . static::buildWhereClause()
                . static::buildGroupByClause()
                . static::buildOrderByClause();
                if (static::$count) {
                    $sql = str_replace('SELECT *', 'SELECT COUNT(*)', $sql);
                }
                break;

            case 'insert':
                $columns = implode(', ', array_keys(static::$insertData));
                $placeholders = implode(', ', array_map(function ($key) {
                    return ":{$key}";
                }, array_keys(static::$insertData)));

                $sql = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";
                break;

            case 'update':
                $setClauses = implode(', ', array_map(function ($key) {
                    return "{$key} = :{$key}";
                }, array_keys(static::$updateData)));

                $sql = "UPDATE " . static::$table . " SET {$setClauses}" . static::buildWhereClause();
                break;

            case 'delete':
                $sql = "DELETE FROM " . static::$table . static::buildWhereClause();
                break;

            default:
                throw new Exception("Unsupported operation type.");
        }
        static::checkTable();

        static::resetConditions();

        return $sql;
    }

    public static function sqlwp()
    {
        static::checkTable();
        switch (static::$operationType) {
            case 'select':
                $sql = "SELECT " . static::buildDistinctClause() . " " . implode(', ', static::$selectedColumns)
                . " FROM " . static::$table
                . static::buildJoinClause()
                . static::buildWhereClause()
                . static::buildGroupByClause()
                . static::buildOrderByClause();
                if (static::$count) {
                    $sql = str_replace('SELECT *', 'SELECT COUNT(*)', $sql);
                }
                break;

            case 'insert':
                $columns = implode(', ', array_keys(static::$insertData));
                $placeholders = implode(', ', array_map(function ($key) {
                    return ":{$key}";
                }, array_keys(static::$insertData)));

                $sql = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";
                break;

            case 'update':
                $setClauses = implode(', ', array_map(function ($key) {
                    return "{$key} = :{$key}";
                }, array_keys(static::$updateData)));

                $sql = "UPDATE " . static::$table . " SET {$setClauses}" . static::buildWhereClause();
                break;

            case 'delete':
                $sql = "DELETE FROM " . static::$table . static::buildWhereClause();
                break;

            default:
                throw new Exception("Unsupported operation type.");
        }
        foreach (static::$parameters as $param => $value) {
            $value = is_string($value) ? "'" . addslashes($value) . "'" : (is_null($value) ? 'NULL' : $value);
            $sql = str_replace($param, $value, $sql);
        }

        
        static::resetConditions();
        
        return $sql;
    }

    private static function runQuery($sql, array $params = [])
    {
        $pdo = static::getConnection();
        $stmt = $pdo->prepare($sql);
        
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }

        $stmt->execute();
        return $stmt;
    }

    public static function countColumn($column)
    {
        static::checkTable();
        static::$operationType = 'select';
        static::$selectedColumns = [
            $column,
            "COUNT(*) AS count",
        ];
        static::$groupBy = [$column];

        $pdo = static::getConnection();

        $sql = "SELECT " . implode(', ', static::$selectedColumns)
        . " FROM " . static::$table
        . static::buildJoinClause()
        . static::buildWhereClause()
        . static::buildGroupByClause()
        . static::buildOrderByClause();

        $params = static::$parameters;
        $stmt = static::runQuery($sql, $params);
        static::resetConditions();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
