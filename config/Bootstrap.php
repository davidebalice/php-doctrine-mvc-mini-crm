<?php
namespace App\Config;

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

require_once __DIR__ . '/../vendor/autoload.php';

// Configura Doctrine
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/../src/Entity'],
    isDevMode: true
);

// Connessione al database (modifica con i tuoi parametri)
$connection = DriverManager::getConnection([
    'dbname' => 'php-test',
    'user' => 'root',
    'password' => '',
    'host' => '127.0.0.1',
    'driver' => 'pdo_mysql',
], $config);

$entityManager = new EntityManager($connection, $config);





