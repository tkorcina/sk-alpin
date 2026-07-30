<?php

define('TMP_DIR',  __DIR__ . '/../temp');
define('LOG_DIR',  __DIR__ . '/../log');
define("WWW_DIR", __DIR__ . '/../www');

require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/shortcuts.php';

$configurator = new ADT\Configurator;

$configurator
	->addDeveloper('TomasKorcina@$2y$10$.cHAjP2KM3TYsm84aixOCuze9Zb77EgF1LJg8VeGRJeAJhAQUQEPy')
	->setDebugMode(false);

$configurator->enableDebugger(LOG_DIR);
$configurator->setTempDirectory(TMP_DIR);

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator
	->setConfigDirectory(__DIR__ . '/config')
	->addConfig('config');

$configurator->addConfig('config.local');

$container = $configurator->createContainer();

\ADT\Forms\Controls\PhoneNumberInput::register();

return $container;
