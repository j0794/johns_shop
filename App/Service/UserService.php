<?php


namespace App\Service;


use App\Model\User;
use App\MySQL\Exceptions\GivenClassNotImplementerITableRowException;
use App\MySQL\Exceptions\QueryException;
use App\Repository\UserRepository;

class UserService
{

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $salt = 'Fd@6k+7+FmhO';

    /**
     * @var UserRepository
     */
    private $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * @param string $password
     *
     * @return string
     */
    public function generatePasswordHash(string $password)
    {
        return $this->md5($this->md5($password));
    }

    /**
     * @param string $str
     *
     * @return string
     */
    private function md5(string $str)
    {
        return md5($str . $this->salt);
    }

    /**
     * @return User
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function getCurrentUser(): User
    {
        $user_id = $_SESSION['user_id'] ?? 0;
        if (!($this->user instanceof User)) {
                $this->user = $this->user_repository->findOrCreate($user_id);
            }
        return $this->user;
    }
}