<?php


namespace App\Model;

/**
 * Class Product
 * @package App\Model
 */
class Product extends AbstractEntity
{
    /**
     * @var string
     */
    protected $table_name = 'products';

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
    protected $name;

    /**
     * @DbColumn()
     *
     * @var float
     */
    protected $price;

    /**
     * @DbColumn()
     *
     * @var int
     */
    protected $amount;

    /**
     * @DbColumn()
     *
     * @var string
     */
    protected $description;
    /**
     * @DbColumn()
     *
     * @var int
     */
    protected $vendor_id;

    /**
     * @var array
     */
    protected $folder_ids;

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return (float) $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return (int) $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string) $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getVendorId(): int
    {
        return (int) $this->vendor_id;
    }

    /**
     * @param int $vendor_id
     */
    public function setVendorId(int $vendor_id): void
    {
        $this->vendor_id = $vendor_id;
    }

    /**
     * @return array
     */
    public function getFolderIds(): array {
        return (array) $this->folder_ids;
    }

    public function addFolderId(int $folder_id): void {
        $this->folder_ids[] = $folder_id;
    }

    public function removeFolderId(int $folder_id): void {
        $index = array_search($folder_id, $this->folder_ids);

        if ($index > -1) {
            unset($this->folder_ids[$index]);
        }
    }

    public function removeAllFolders(): void {
        $this->folder_ids = [];
    }

    public function isFolderIdExist(int $folder_id): bool {
        return in_array($folder_id, $this->getFolderIds());
    }
}