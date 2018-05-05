<?php

namespace RudyMas\PDOExt;

use Exception;
use PDO;
use PDOException;

/**
 * Class DBconnect (PHP version 7.1)
 *
 * @author      Rudy Mas <rudy.mas@rmsoft.be>
 * @copyright   2014-2018, rmsoft.be. (http://www.rmsoft.be/)
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version     5.3.5.75
 * @package     RudyMas\PDOExt
 */
class DBconnect extends PDO
{
    public $rows, $data;
    private $result, $internalData;

    /**
     * DBconnect constructor.
     * @param string $host
     * @param int $port
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @param string $charset
     * @param string $dbtype
     * @throws Exception
     */
    public function __construct(string $host = 'localhost',
                                int $port = 3306,
                                string $username = 'username',
                                string $password = 'password',
                                string $dbname = 'dbname',
                                string $charset = 'utf8',
                                string $dbtype = 'mysql')
    {
        try {
            switch (strtolower($dbtype)) {
                case 'mysql':
                    parent::__construct("mysql:host={$host};port={$port};charset={$charset};dbname={$dbname}", $username, $password);
                    // parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                    parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    parent::setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
                    break;
                case 'mssql':
                    parent::__construct("sqlsrv:server = tcp:{$host},{$port}; Database = {$dbname}", $username, $password);
                    parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    parent::setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
                    break;
                default:
                    throw new Exception("$dbtype isn't implemented yet!", 404);
            }
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $query
     * @param int $mode
     * @param null $arg3
     * @param array $ctorargs
     */
    public function query(string $query, int $mode = PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null, array $ctorargs = array()): void
    {
        try {
            $this->result = parent::query($query, $mode, $arg3, $ctorargs);
            $this->internalData = $this->result->fetchAll(PDO::FETCH_ASSOC);
            $this->rows = count($this->internalData);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $query
     */
    private function execQuery(string $query): void
    {
        try {
            $this->rows = parent::exec($query);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param int $row
     */
    public function fetch(int $row): void
    {
        $this->data = $this->internalData[$row];
    }

    public function fetchAll(): void
    {
        $this->data = $this->internalData;
    }

    /**
     * @param string $query
     * @param string $field
     * @return string
     */
    public function queryItem(string $query, string $field): string
    {
        try {
            $this->query($query);
            $this->fetch(0);
            return $this->data[$field];
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $query
     * @return bool
     */
    public function queryRow(string $query): bool
    {
        try {
            $this->query($query);
            if ($this->rows == 0) return false;
            $this->fetch(0);
            return true;
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $query
     */
    public function insert(string $query): void
    {
        try {
            $this->execQuery($query);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $query
     */
    public function update(string $query): void
    {
        try {
            $this->execQuery($query);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $query
     */
    public function delete(string $query): void
    {
        try {
            $this->execQuery($query);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $statement
     * @param array $options
     */
    public function prepare(string $statement, array $options = []): void
    {
        try {
            $this->result = parent::prepare($statement, $options);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param $parameter
     * @param $variable
     * @param int $dataType
     *      - PDO::PARAM_BOOL = Represents a boolean data type
     *      - PDO::PARAM_NULL = Represents the SQL NULL data type
     *      - PDO::PARAM_INT = Represents the SQL INTEGER data type
     *      - PDO::PARAM_STR = Represents the SQL CHAR, VARCHAR, or other string data type
     *      - For more information: <a href="http://php.net/manual/en/pdo.constants.php">Predefined Constants</a>
     * @param null $length
     * @param null $driver_options
     */
    public function bindParam($parameter, $variable, $dataType = PDO::PARAM_STR, $length = null, $driver_options = null): void
    {
        try {
            $this->result->bindParam($parameter, $variable, $dataType, $length, $driver_options);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param $parameter
     * @param $value
     * @param int $dataType
     *      - PDO::PARAM_BOOL = Represents a boolean data type
     *      - PDO::PARAM_NULL = Represents the SQL NULL data type
     *      - PDO::PARAM_INT = Represents the SQL INTEGER data type
     *      - PDO::PARAM_STR = Represents the SQL CHAR, VARCHAR, or other string data type
     *      - For more information: <a href="http://php.net/manual/en/pdo.constants.php">Predefined Constants</a>
     */
    public function bindValue($parameter, $value, $dataType = PDO::PARAM_STR): void
    {
        try {
            $this->result->bindValue($parameter, $value, $dataType);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param array|null $inputParams
     */
    public function execute(array $inputParams = null): void
    {
        try {
            if ($this->result->execute($inputParams)) {
                $this->internalData = $this->result->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param null|string $name
     * @return string
     */
    public function lastInsertId($name = null): string
    {
        return parent::lastInsertId($name);
    }

    /**
     * functon cleanSQL($content)
     * Quotes a string for use in a query.
     *
     * @param string|null $content
     * @return string
     */
    public function cleanSQL(string $content = null): string
    {
        try {
            return $content === null ? parent::quote(null) : parent::quote($content);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

}

/** End of File: DBconnect.php **/