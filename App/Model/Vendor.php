<?php


namespace App\Model;


class Vendor extends AbstractEntity
{
    /**
     * @var string
     */
    protected $table_name = 'vendors';

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


}