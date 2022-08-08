<?php

namespace RudyMas;

use Exception;
use PDO;

/**
 * Class DBconnect (PHP version 8.1)
 *
 * @author      Rudy Mas <rudy.mas@rmsoft.be>
 * @copyright   2014-2022, rmsoft.be. (http://www.rmsoft.be/)
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version     8.1.0.0
 * @package     RudyMas
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
     * @param string $timezone
     * @throws Exception
     */
    public function __construct(
        string $host = 'localhost',
        int    $port = 3306,
        string $username = 'username',
        string $password = 'password',
        string $dbname = 'dbname',
        string $charset = 'utf8',
        string $dbtype = 'mysql',
        string $timezone = 'Europe/Brussels'
    )
    {
        switch (strtolower($dbtype)) {
            case 'mysql':
                parent::__construct("mysql:host={$host};port={$port};charset={$charset};dbname={$dbname}", $username, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '{$timezone}'"]);
                // parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                parent::setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
                break;
            case 'mssql':
            case 'sybase':
                parent::__construct("sqlsrv:server = tcp:{$host},{$port}; Database = {$dbname}", $username, $password);
                parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                parent::setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
                break;
            case 'odbc_mssql':
            case 'odbc_sybase':
                parent::__construct("odbc:Driver={ODBC Driver 17 for SQL Server};Server={$host},{$port};Database={$dbname}", $username, $password);
                parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                parent::setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
                break;
            default:
                throw new Exception("$dbtype isn't implemented yet!", 404);
        }
    }

    /**
     * @param  string  $statement
     * @param  int|null  $mode
     * @param  mixed  ...$fetch_mode_args
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function query(string $statement, ?int $mode = PDO::ATTR_DEFAULT_FETCH_MODE, mixed ...$fetch_mode_args): void
    {
        $this->result = parent::query($statement, $mode, ...$fetch_mode_args);
        $this->internalData = $this->result->fetchAll(PDO::FETCH_ASSOC);
        $this->rows = count($this->internalData);
    }

    /**
     * @param string $query
     */
    private function execQuery(string $query): void
    {
        $this->rows = parent::exec($query);
    }

    public function fetchAll(): void
    {
        $this->data = $this->internalData;
    }

    /**
     * @param int $row
     * @deprecated
     */
    public function fetch(int $row): void
    {
        trigger_error('Use "fetchRow" instead.', E_USER_DEPRECATED);
        $this->fetchRow($row);
    }

    /**
     * @param int $row
     */
    public function fetchRow(int $row): void
    {
        $this->data = $this->internalData[$row];
    }

    /**
     * @param string $query
     * @return bool
     */
    public function queryRow(string $query): bool
    {
        $this->query($query);
        if ($this->rows == 0) return false;
        $this->fetchRow(0);
        return true;
    }

    /**
     * @param string $query
     * @param string $field
     * @return string
     */
    public function queryItem(string $query, string $field): string
    {
        $this->query($query);
        $this->fetchRow(0);
        return $this->data[$field];
    }

    /**
     * @param string $query
     */
    public function insert(string $query): void
    {
        $this->execQuery($query);
    }

    /**
     * @param string $query
     */
    public function update(string $query): void
    {
        $this->execQuery($query);
    }

    /**
     * @param string $query
     */
    public function delete(string $query): void
    {
        $this->execQuery($query);
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
        return $content === null ? parent::quote(null) : parent::quote($content);
    }
}
