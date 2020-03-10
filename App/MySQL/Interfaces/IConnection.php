<?php


namespace App\MySQL\Interfaces;


interface IConnection
{
    public function __construct(string $host, string $user_name, string $user_pwd, string $database);

    public function getConnect();
}