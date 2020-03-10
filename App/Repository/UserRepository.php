<?php


namespace App\Repository;


use App\Model\User;

/**
 * Class UserRepository
 * @package App\Repository
 *
 * @method User find(int $id)
 * @method User[] findAll()
 * @method User[] findAllWithLimit(int $limit = 50, int $start = 0)
 */
class UserRepository extends AbstractRepository
{
    protected $model = User::class;

    /**
     * @param string $name
     *
     * @return User
     */
    public function findByName(string $name): User
    {
        $name = $this->odm->escape($name);
        $query = "SELECT * FROM " . $this->table_name . " WHERE name = '$name'";
        return $this->odm->fetchRow($query, $this->model);

    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isEmailExist(string $email): bool
    {
        $email = $this->odm->escape($email);
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = '$email'";
        $result = $this->odm->fetchRow($query, $this->model);

        return !is_null($result);
    }
}