<?php
namespace App\Database;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

class Database
{
    protected PDO $pdo;
    protected PDOStatement $stmt;

    /**
     * Create a new database connection.
     *
     * @param string $host     The database host.
     * @param string $dbname   The database name.
     * @param string $username The database username.
     * @param string $password The database password.
     *
     * @throws Exception
     */
    public function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            // Set the PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    /**
     * Execute a query.
     *
     * @param string $query The query to execute.
     *
     * @return void
     */
    public function query($query)
    {
        try {
            $this->stmt = $this->pdo->query($query);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Execute a prepared statement.
     *
     * @param array $params The parameters to bind.
     *
     * @return PDOStatement
     */
    public function prepare($query): PDOStatement
    {
        $this->stmt = $this->pdo->prepare($query);
        return $this->stmt;
    }

    /**
     * Bind a parameter to a value.
     *
     * @param string $param The parameter to bind.
     * @param mixed  $value The value to bind.
     *
     * @return int
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
