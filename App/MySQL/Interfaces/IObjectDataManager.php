<?php


namespace App\MySQL\Interfaces;


interface IObjectDataManager
{

    public function __construct(IConnection $connection, IArrayDataManager $arrayDataManager);

    /**
     * @param string $query
     * @param string $class_name
     *
     * @return ITableRow|null
     */
    public function fetchRow(string $query, string $class_name): ?ITableRow;

    /**
     * @param string $query
     * @param string $class_name
     * @return ITableRow[]
     */
    public function fetchAll(string $query, string $class_name): array;


    /**
     * @param string $query
     * @param string $hash_key
     * @param string $class_name
     * @return ITableRow[]
     */
    public function fetchAllHash(string $query, string $hash_key, string $class_name): array;

    /**
     * @param ITableRow $row
     * @return ITableRow
     */
    public function save(ITableRow $row): ITableRow;

    /**
     * @param string $table_name
     * @param ITableRow[] $rows
     * @return ITableRow[]
     */
    public function saveMany(string $table_name, array $rows): array;


    /**
     * @param ITableRow $row
     * @return int
     */
    public function delete(ITableRow $row): int;

    /**
     * @return IArrayDataManager
     */
    public function getArrayDataManager(): IArrayDataManager;
}