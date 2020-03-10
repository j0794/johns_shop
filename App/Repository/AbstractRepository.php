<?php


namespace App\Repository;


use App\Model\AbstractEntity;
use App\Model\Model;
use App\MySQL\Exceptions\GivenClassNotImplementerITableRowException;
use App\MySQL\Exceptions\QueryException;
use App\MySQL\Interfaces\IObjectDataManager;
use App\Repository\Exceptions\ModelShouldBeAAbstractEntityException;

abstract class AbstractRepository
{

    /**
     * @var string
     */
    protected $model;

    /**
     * @var IObjectDataManager
     */
    protected $odm;

    /**
     * @var mixed
     */
    protected $table_name;

    /**
     * AbstractRepository constructor.
     * @param IObjectDataManager $odm
     *
     * @throws \Exception
     */
    public function __construct(IObjectDataManager $odm)
    {
        if (!class_exists($this->model) || !in_array(AbstractEntity::class, class_parents($this->model))) {
            throw new ModelShouldBeAAbstractEntityException('Model should be a AbstractEntity');
        }
        $this->table_name = $this->getTableName();
        $this->odm = $odm;
    }

    /**
     * @param AbstractEntity $entity
     *
     * @return AbstractEntity
     */
    public function save(AbstractEntity $entity): AbstractEntity
    {
        /**
         * @var $result AbstractEntity
         */
        $result = $this->odm->save($entity);
        return $result;
    }

    /**
     * @param AbstractEntity $entity
     *
     * @return int
     */
    public function delete(AbstractEntity $entity): int
    {
        return $this->odm->delete($entity);
    }

    /**
     * @param int $id
     *
     * @return mixed
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function find(int $id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = $id";
        $result = $this->odm->fetchRow($query, $this->model);
        return $result ? $this->modifyResultItem($result) : null;

    }

    /**
     * @return mixed
     */
    public function create()
    {
        return new $this->model;
    }

    /**
     * @param int $id
     *
     * @return mixed
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function findOrCreate(int $id)
    {
        if ($id > 0) {
            return $this->find($id);
        }
        return $this->create();
    }

    /**
     * @param string|null $where_data
     *
     * @return array
     */
    public function findAll(string $where_data = null)
    {
        $query = "SELECT * FROM " . $this->table_name;
        if ($where_data) {
            $query .= ' WHERE ' . $where_data;
        }
        $result = $this->odm->fetchAllHash($query, 'id', $this->model);
        return $this->modifyResultList($result);

    }

    /**
     * @param int $limit
     * @param int $start
     * @param string|null $where_data
     *
     * @return array
     */
    public function findAllWithLimit(int $limit = 50, int $start = 0, string $where_data = null)
    {
        $query = "SELECT * FROM " . $this->table_name;
        if ($where_data) {
            $query .= ' WHERE ' . $where_data;
        }
        $query .= " LIMIT $start, $limit";
        $result = $this->odm->fetchAllHash($query, 'id', $this->model);
        return $this->modifyResultList($result);
    }


    /**
     * @param string|null $where_data
     *
     * @return int
     */
    public function getCount(string $where_data = null)
    {
        $query = "SELECT COUNT(1) as count FROM " . $this->table_name;
        if ($where_data) {
            $query .= ' WHERE ' . $where_data;
        }
        /**
         * @var $result Model
         */
        $result = $this->odm->fetchRow($query, Model::class);
        return (int) $result->getColumnValue('count') ?? 0;
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    private function getTableName()
    {
        $model = new $this->model;
        $object = new \ReflectionObject($model);
        $property = $object->getProperty('table_name');
        $property->setAccessible(true);
        return $property->getValue($model);
    }

    /**
     * @param AbstractEntity $item
     *
     * @return mixed
     */
    protected function modifyResultItem(AbstractEntity $item)
    {
        $list = [
            0 => $item,
        ];
        $result = $this->modifyResultList($list);
        return $result[0];
    }

    protected function modifyResultList(array $result)
    {
        return $result;
    }
}