<?php


namespace App\Model;


class User extends AbstractEntity
{
    /**
     * @var string
     */
    protected $table_name = 'users';

    /**
     * @DbColumn(immutable)
     *
     * @var int
     */
    protected $id = 0;

    /**
     * @DbColumn()
     *
     * @var string
     */
    protected $name = '';

    /**
     * @DbColumn()
     *
     * @var string
     */
    protected $email = '';

    /**
     * @DbColumn()
     *
     * @var string
     */
    protected $password = '';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

}