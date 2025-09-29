<?php
require_once __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;

// Entity class path
$paths = [__DIR__ . "/src/Entity"];
$isDevMode = true;


// Annotation Driver
$config = new Configuration();
$config->setProxyDir(__DIR__ . '/var/proxies');
$config->setProxyNamespace('Proxies');
$config->setAutoGenerateProxyClasses($isDevMode);
$config->setNamingStrategy(new UnderscoreNamingStrategy());

$driver = new AttributeDriver($paths);
$config->setMetadataDriverImpl($driver);

// database config
$connection = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'dbname' => 'cms_db',
    'user' => 'root',
    'password' => 'Mysql@123',
], $config);

// EntityManager
$entityManager = new EntityManager($connection, $config);


