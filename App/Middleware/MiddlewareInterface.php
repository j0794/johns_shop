<?php


namespace App\Middleware;


use App\Router\Route;

interface MiddlewareInterface
{
    public function run(Route $route);
}