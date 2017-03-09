<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Debug\Debug;

ini_set('display_errors', 0);

date_default_timezone_set("utc");

require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../config/prod.php';
require __DIR__.'/../src/controllers.php';


$app['doctrine'] = function(){

	/**
	 * Jesus Arriaga Barron - Blue sports test
	 * configuration doctrine -  since there is not differences between using Mysql or Postgresql
	 * I will use mysql instead since my server has mysql running...
	 * I will comment the config if the db would be postgresql
	 *
	 */


	$paths = array(__DIR__."/../src/models");
	$isDevMode = false;

//configure database
	$dbParams = array(
		'driver'   => 'pdo_mysql', //'driver'   => 'pdo_pgsql',  //for POSTGRESQL
		'user'     => 'root',
		'password' => 'root',
		'dbname'   => 'todo',
		'host'	=> '127.0.0.1'
	);


	$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
	return $entityManager = EntityManager::create($dbParams, $config);
};


Debug::enable();

$app->run();
