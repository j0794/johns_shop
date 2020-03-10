<?php

use App\Model\Product;
use App\Repository\FolderRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;

require_once __DIR__ . '/../App/bootstrap.php';

$faker = Faker\Factory::create();
$person = new Faker\Provider\en_US\Person($faker);

$folder_repository = $container->get(FolderRepository::class);
$vendor_repository = $container->get(VendorRepository::class);
$product_repository = $container->get(ProductRepository::class);

for ($i = 0; $i < $faker->numberBetween(400, 500); $i++) {
    $product = new Product();
    $product->setName($person->name());
    $product->setAmount($faker->numberBetween(0, 90));
    $product->setPrice($faker->randomFloat(2, 9, 100));
    $product->setDescription($faker->realText());
    $vendor = $vendor_repository->getRandom();
    $product->setVendorId($vendor->getId());
    for ($i2 = 0; $i2 < $faker->numberBetween(1, 5); $i2++) {
        $folder = $folder_repository->getRandom();
        $product->addFolderId($folder->getId());
    }
    echo $product->getName() . "\n";
    $product_repository->save($product);
}
