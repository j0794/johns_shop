<?php


namespace App\MySQL;

use App\MySQL\Interfaces\ITableRow;
use App\MySQL\Exceptions\GivenClassNotImplementerITableRowException;
use App\MySQL\Exceptions\QueryException;
use App\MySQL\Interfaces\IArrayDataManager;
use App\MySQL\Interfaces\IConnection;
use App\MySQL\Interfaces\IObjectDataManager;

class ObjectDataManager extends AbstractDataManager implements IObjectDataManager
{

    /**
     * @var IArrayDataManager
     */
    protected $arrayDataManager;

    public function __construct(IConnection $connection, IArrayDataManager $arrayDataManager)
    {
        $this->connection = $connection;
        $this->arrayDataManager = $arrayDataManager;
    }

    /**
     * @param string $query
     * @param string $class_name
     *
     * @return ITableRow|null
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function fetchRow(string $query, string $class_name): ?ITableRow
    {
        $this->isITableRowClass($class_name);
        $result = $this->query($query);
        /**
         * @var ITableRow $row
         */
        $row = mysqli_fetch_object($result, $class_name);
        return $row;
    }

    /**
     * @param string $query
     * @param string $class_name
     *
     * @return ITableRow[]
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function fetchAll(string $query, string $class_name): array
    {
        $this->isITableRowClass($class_name);
        $result = $this->query($query);
        $data = [];
        while($row = mysqli_fetch_object($result, $class_name)) {
            /**
             * @var ITableRow $row
             */
            $data[] = $row;
        }
        return $data;
    }


    /**
     * @param string $query
     * @param string $class_name
     * @param string $hash_key
     *
     * @return ITableRow[]
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function fetchAllHash(string $query, string $hash_key, string $class_name): array {
        $this->isITableRowClass($class_name);
        $result = $this->query($query);
        $data = [];
        while($row = mysqli_fetch_object($result, $class_name)) {
            /**
             * @var $row ITableRow
             */
            $key = $row->getColumnValue($hash_key);
            if (is_null($key)) {
                continue;
            }
            $data[$key] = $row;
        }
        return $data;
    }

    public function delete(ITableRow $row): int {
        $id = $row->getPrimaryKeyValue();
        $primary_key = $row->getPrimaryKey();
        return $this->arrayDataManager->delete($row->getTableName(), [
            $primary_key => $id,
        ]);
    }

    /**
     * @param ITableRow $row
     *
     * @return ITableRow
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function save(ITableRow $row): ITableRow
    {
        if ($row->getPrimaryKeyValue() > 0) {
            return $this->update($row);
        }
        return $this->insert($row);
    }

    /**
     * @param ITableRow $row
     * @return ITableRow
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    protected function update(ITableRow $row): ITableRow {
        $data = $row->getColumnsForUpdate();
        $id = $row->getPrimaryKeyValue();
        $primary_key = $row->getPrimaryKey();
        $this->arrayDataManager->update($row->getTableName(), $data, [
            $primary_key => $id,
        ]);
        $table_name = $row->getTableName();
        $query = "SELECT * FROM $table_name WHERE $primary_key = $id";
        return $this->fetchRow($query, get_class($row));
    }

    /**
     * @param ITableRow $row
     * @return ITableRow
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    protected function insert(ITableRow $row): ITableRow {
        $data = $row->getColumnsForInsert();
        $id = $this->arrayDataManager->insert($row->getTableName(), $data);
        $primary_key = $row->getPrimaryKey();
        $table_name = $row->getTableName();
        $query = "SELECT * FROM $table_name WHERE $primary_key = $id";
        return $this->fetchRow($query, get_class($row));
    }

    public function saveMany(string $table_name, array $rows): array
    {
        return [];
    }

    /**
     * @return IArrayDataManager
     */
    public function getArrayDataManager(): IArrayDataManager {
        return $this->arrayDataManager;
    }

    /**
     * @param string $class_name
     * @throws GivenClassNotImplementerITableRowException
     */
    private function isITableRowClass(string $class_name) {
        $is_class_exists = class_exists($class_name);
        $class_implements = class_implements($class_name);
        $is_class_implements = in_array(ITableRow::class, $class_implements);

        if ($is_class_exists && $is_class_implements) {
            return;
        }

        $message = "$class_name not implemented ITableRow";
        throw new GivenClassNotImplementerITableRowException($message);
    }


}