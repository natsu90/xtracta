<?php

require_once "vendor/autoload.php";

use Ahc\Cli\Application;
use Xtracta\SeedCommand;
use Xtracta\ImportCommand;
use Xtracta\ReadCommand;

// Init App with name and version
$app = new Application('Xtracta CLI', 'v0.0.1');

// Add commands with optional aliases`
$app->add(new SeedCommand, 's');
$app->add(new ImportCommand, 'i');
$app->add(new ReadCommand, 'r');

// Set logo
$app->logo(
<<<EOF
 __  ___                  _           ____ _     ___ 
 \ \/ / |_ _ __ __ _  ___| |_ __ _   / ___| |   |_ _|
  \  /| __| '__/ _` |/ __| __/ _` | | |   | |    | | 
  /  \| |_| | | (_| | (__| || (_| | | |___| |___ | | 
 /_/\_\\__|_|  \__,_|\___|\__\__,_|  \____|_____|___|
                                                     
EOF
);

$app->handle($_SERVER['argv']);