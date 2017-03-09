<?php
/**
 * Created by PhpStorm.
 * User: jbarron
 * Date: 3/8/17
 * Time: 11:13 PM
 */
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;

/**
 * Jesus Arriaga Barron - Blue sports test
 * configuration doctrine -  since there is not differences between using Mysql or Postgresql
 * I will use mysql instead since my server has mysql running...
 * I will comment the config if the db would be postgresql
 *
 */


$paths = array(__DIR__."/src/models");
$isDevMode = false;

//configure database
$dbParams = array(
	'driver'   => 'pdo_mysql',
	'user'     => 'root',
	'password' => 'root',
	'dbname'   => 'todo',
	'host'	=> '127.0.0.1'
);

/* configuration for postgresql   ;-)
$dbParams = array(
    'driver'   => 'pdo_pgsql',  //hello little driver
    'user'     => 'root',
    'password' => 'root',
    'dbname'   => 'todos',
);*/

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

return ConsoleRunner::createHelperSet($entityManager);
