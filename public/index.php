<?php

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|ico)$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/../App/bootstrap.php';
    $kernel->run();
}