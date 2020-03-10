<?php

use App\Model\Vendor;
use App\Repository\VendorRepository;

require_once __DIR__ . '/../App/bootstrap.php';

$faker = Faker\Factory::create();
$company = new Faker\Provider\en_US\Company($faker);

$vendor_repository = $container->get(VendorRepository::class);

for ($i = 0; $i < 10; $i++) {
    $vendor = new Vendor();
    $vendor->setName($company->company());
    echo $vendor->getName() . "\n";
    $vendor_repository->save($vendor);
}
