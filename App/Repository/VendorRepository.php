<?php


namespace App\Repository;


use App\Model\AbstractEntity;
use App\Model\Vendor;

class VendorRepository extends AbstractRepository
{

    protected $model = Vendor::class;

    /**
     * @return AbstractEntity|null
     */
    public function getRandom() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY RAND() LIMIT 1";
        return $this->odm->fetchRow($query, $this->model);
    }
}