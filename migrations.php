<?php

use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Tools\Console\Command\AbstractCommand;
use Doctrine\Migrations\Tools\Console\ConsoleRunner;

require_once "vendor/autoload.php";

$configuration = new Configuration($connection);
$configuration->setMigrationsDirectory("path/to/migrations"); // Definisci il percorso delle tue migrazioni
$configuration->setMigrationsNamespace("App\Migrations"); // Namespace delle migrazioni
$configuration->setMigrationsTableName("doctrine_migration_versions"); // Nome della tabella delle versioni delle migrazioni

// Inizializza il comando
ConsoleRunner::run($configuration);
