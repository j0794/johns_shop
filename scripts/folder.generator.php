<?php

use App\Model\Folder;
use App\Repository\FolderRepository;

require_once __DIR__ . '/../App/bootstrap.php';

$faker = Faker\Factory::create();
$address = new Faker\Provider\en_US\Address($faker);

$folder_repository = $container->get(FolderRepository::class);

for ($i = 0; $i < $faker->numberBetween(10, 30); $i++) {
    $folder = new Folder();
    $folder->setName($address->city());
    echo $folder->getName() . "\n";
    $folder_repository->save($folder);
}
