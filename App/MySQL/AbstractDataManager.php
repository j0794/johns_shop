<?php


namespace App\MySQL;


use App\MySQL\Exceptions\QueryException;
use App\MySQL\Interfaces\IConnection;
use mysqli_result;

abstract class AbstractDataManager
{
    /**
     * @var IConnection
     */
    protected $connection;

    public function escape(string $value)
    {
        return mysqli_real_escape_string($this->getConnect(), $value);
    }

    /**
     * @return IConnection
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return mixed
     */
    protected function getConnect()
    {
        return $this->getConnection()->getConnect();
    }

    /**
     * @param string $query
     *
     * @return bool|mysqli_result
     * @throws QueryException
     */
    protected function query(string $query)
    {
        $result = mysqli_query($this->getConnect(), $query);
        $this->checkErrors();
        return $result;
    }

    /**
     * @throws QueryException
     */
    protected function checkErrors()
    {
        $mysqli_errno = mysqli_errno($this->getConnect());
        if (!$mysqli_errno) {
            return;
        }
        $mysqli_error = mysqli_error($this->getConnect());
        $message = "MySQL query error: ($mysqli_errno) $mysqli_error";
        throw new QueryException($message);
    }
}