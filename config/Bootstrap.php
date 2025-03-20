<?php
namespace App\Config;

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;


require_once __DIR__ . '/../vendor/autoload.php';

// Configura Doctrine
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/../src/Entity'],
    isDevMode: true
);

//demo mode
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
define('DEMO_MODE', filter_var($_ENV['DEMO_MODE'] ?? false, FILTER_VALIDATE_BOOLEAN));

// Connessione al database (modifica con i tuoi parametri)
$connection = DriverManager::getConnection([
    'dbname' => 'crm-doctrine',
    'user' => 'root',
    'password' => '',
    'host' => '127.0.0.1',
    'driver' => 'pdo_mysql',
], $config);

$entityManager = new EntityManager($connection, $config);

return $entityManager;



