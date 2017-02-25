<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode('192.168.33.1'); // enable for your remote IP
$configurator->enableTracy(__DIR__ . '/../var/log');

$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(__DIR__ . '/../var/temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

\Tracy\Dumper::$objectExporters[\Ramsey\Uuid\Uuid::class] = function (\Ramsey\Uuid\Uuid $uuid): array {
	return ['value' => $uuid->toString()];
};

return $container;
