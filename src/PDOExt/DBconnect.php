<?php
namespace RudyMas\PDOExt;
use PDO;
use PDOException;

/**
 * DBconnect -> PDO (Uses UTF8 character set)
 *
 * @author      Rudy Mas <rudy.mas@rudymas.be>
 * @copyright   2014-2016, rudymas.be. (http://www.rudymas.be/)
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version     3.0.1
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
    public function __construct($host = 'localhost', $username = 'username', $password = 'password', $dbname = 'dbname', $charset = 'utf8', $dbtype = 'mysql')
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
     * @throws PDOException
     */
    public function query($query)
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
     * @throws PDOException
     */
    private function execQuery($query)
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
    public function fetch($row)
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
     * @throws PDOException
     * @return mixed
     */
    public function queryItem($query, $field)
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
     * @throws PDOException
     * @return mixed Returns FALSE if no data was found
     */
    public function queryRow($query)
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
     * @throws PDOException
     */
    public function insert($query)
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
     * @throws PDOException
     */
    public function update($query)
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
     * @throws PDOException
     */
    public function delete($query)
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
     * @param array $options (Default = NULL)
     * @throws PDOException
     */
    public function prepare($statement, $options = NULL)
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
     * @throws PDOException
     */
    public function bindParam($paramno, $param, $type, $maxlen, $driverdata)
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
     * @throws PDOException
     */
    public function bindValue($paramno, $param, $type)
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
     * @throws PDOException
     */
    public function execute($bound_input_params)
    {
        try {
            $this->rows = $this->result->execute($bound_input_params);
            if ($this->rows > 0) {
                $this->internalData = $this->result->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * functon cleanSQL($inhoud)
     * Quotes a string for use in a query.
     *
     * @param string $inhoud
     * @throws PDOException
     * @return string $uitvoer
     */
    public function cleanSQL($inhoud)
    {
        try {
            $inhoud = htmlentities($inhoud, ENT_HTML5, 'UTF-8');
            if ($inhoud == NULL) {
                $uitvoer = parent::quote(NULL);
            } else {
                $uitvoer = parent::quote($inhoud);
            }
            return $uitvoer;
        } catch (PDOException $exception) {
            throw $exception;
        }
    }
}
/** End of File: DBconnect.php **/