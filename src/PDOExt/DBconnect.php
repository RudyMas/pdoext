<?php
namespace RudyMas\PDOExt;

use PDO;
use PDOException;

/**
 * Class DBconnect (PHP version 7.0)
 *
 * @author      Rudy Mas <rudy.mas@rmsoft.be>
 * @copyright   2014-2016, rmsoft.be. (http://www.rmsoft.be/)
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version     4.0.1
 * @package     RudyMas\PDOExt
 */
class DBconnect extends PDO
{
    public $rows, $data;
    private $result, $internalData;

    /**
     * DBconnect constructor.
     *
     * The parameters can be configured to have default values
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @param string $charset
     * @param string $dbtype
     * @throws PDOException
     */
    public function __construct(string $host = 'localhost', string $username = 'username', string $password = 'password', string $dbname = 'dbname', string $charset = 'utf8', string $dbtype = 'mysql')
    {
        try {
            switch (strtolower($dbtype)) {
                case 'mysql':
                    parent::__construct('mysql:host=' . $host . ';charset=' . $charset . ';dbname=' . $dbname, $username, $password);
                    // parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                    parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    break;
                default:
                    die ($dbtype . ' isn\'t implemented yet!');
            }
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * function query($query)
     * Use this for SELECT queries
     *
     * @param string $query
     * @return \PDOStatement|void
     */
    public function query(string $query): void
    {
        try {
            $this->result = parent::query($query);
            $this->rows = $this->result->rowCount();
            if ($this->rows > 0) {
                $this->internalData = $this->result->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * function execQuery($query)
     * This function is used for internal queries which don't use SELECT
     *
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
     * function fetch($row)
     * This function will fetch one row from the executed query
     *
     * @param int $row
     */
    public function fetch(int $row): void
    {
        $this->data = $this->internalData[$row];
    }

    /**
     * function fetchAll()
     * This function will fetch all rows from the executed query
     */
    public function fetchAll()
    {
        $this->data = $this->internalData;
    }

    /**
     * function queryItem($query, $field)
     * This function will get the data from a certain field from the executed query
     *
     * @param string $query
     * @param string $field
     * @return array
     */
    public function queryItem(string $query, string $field): array
    {
        try {
            $this->query($query);
            $this->fetch(0);
            return ($this->data[$field]);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * function queryRow($query)
     * This function will retrieve the first row from the executed query
     *
     * @param string $query
     * @return mixed
     */
    public function queryRow(string $query): mixed
    {
        try {
            $this->query($query);
            if ($this->rows == 0) return FALSE;
            $this->fetch(0);
            return $this->data;
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * function insert($query)
     * Use this function to insert data in the database
     *
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
     * function update($query)
     * Use this function to update data in the database
     *
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
     * function delete($query)
     * Use this function to delete data from the database
     *
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
     * function prepare($statement, $options)
     * Use this function when you want to use a prepared statement
     *
     * @param string $statement
     * @param null $options
     * @return \PDOStatement|void
     */
    public function prepare($statement, $options = null): void
    {
        try {
            $this->result = parent::prepare($statement, $options);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * function bindParam($paramno, $param, $type, $maxlen, $driverdata)
     * Binds a parameter to the specified variable name
     *
     * @param mixed $paramno
     * @param mixed $param
     * @param int $type
     *      - PDO::PARAM_BOOL = Represents a boolean data type
     *      - PDO::PARAM_NULL = Represents the SQL NULL data type
     *      - PDO::PARAM_INT = Represents the SQL INTEGER data type
     *      - PDO::PARAM_STR = Represents the SQL CHAR, VARCHAR, or other string data type
     *      - For more information: <a href="http://php.net/manual/en/pdo.constants.php">Predefined Constants</a>
     * @param int $maxlen
     * @param mixed $driverdata
     */
    public function bindParam(mixed $paramno, mixed $param, int $type, int $maxlen, mixed $driverdata): void
    {
        try {
            $this->result->bindParam($paramno, $param, $type, $maxlen, $driverdata);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * function bindValue($paramno, $param, $type)
     * Binds a value to a parameter
     *
     * @param mixed $paramno
     * @param mixed $param
     * @param int $type
     *      - PDO::PARAM_BOOL = Represents a boolean data type
     *      - PDO::PARAM_NULL = Represents the SQL NULL data type
     *      - PDO::PARAM_INT = Represents the SQL INTEGER data type
     *      - PDO::PARAM_STR = Represents the SQL CHAR, VARCHAR, or other string data type
     *      - For more information: <a href="http://php.net/manual/en/pdo.constants.php">Predefined Constants</a>
     */
    public function bindValue(mixed $paramno, mixed $param, int $type): void
    {
        try {
            $this->result->bindValue($paramno, $param, $type);
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * function execute($bound_input_params)
     * Executes a prepared statement
     *
     * @param array $bound_input_params
     */
    public function execute(array $bound_input_params = []): void
    {
        try {
            if ($this->result->execute($bound_input_params)) {
                $this->internalData = $this->result->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * functon cleanSQL($content)
     * Quotes a string for use in a query.
     *
     * @param string $content
     * @return string
     */
    public function cleanSQL(string $content): string
    {
        try {
            $content = htmlentities($content, ENT_HTML5, 'UTF-8');
            if ($content == null) {
                $output = parent::quote(null);
            } else {
                $output = parent::quote($content);
            }
            return $output;
        } catch (PDOException $exception) {
            throw $exception;
        }
    }
}
/** End of File: DBconnect.php **/