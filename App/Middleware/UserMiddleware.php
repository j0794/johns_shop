<?php


namespace App\Middleware;


use App\MySQL\Exceptions\GivenClassNotImplementerITableRowException;
use App\MySQL\Exceptions\QueryException;
use App\Router\Route;
use App\Service\UserService;

class UserMiddleware implements MiddlewareInterface
{
    /**
     * @var UserService
     */
    private $user_service;

    /**
     * UserMiddleware constructor.
     * @param UserService $user_service
     */
    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    /**
     * @param Route $route
     *
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function run(Route $route)
    {
        $controller = $route->getController();
        $user = $this->user_service->getCurrentUser();
        $controller->addSharedData('user', $user);
    }
}