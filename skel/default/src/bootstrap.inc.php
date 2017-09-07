<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 11.08.17
 * Time: 15:38
 */


namespace bootstrap;

use Gismo\Component\Config\AppConfig;
use Gismo\Component\Config\ConfigLoader;
use Gismo\Component\Plugin\AppLauncher;
use YourProject\App\YourApp;


# Define some constants

define ("CONF_MONGO_CONNECTION", "mongodb://localhost:27017");

# Setup environment
ini_set("display_errors", 1);
date_default_timezone_set("Europe/Berlin");


# Load Composer autoloader
define("GISMO_BOOTSTRAP_FILE", __DIR__ . "/../vendor/autoload.php");
if ( ! file_exists(GISMO_BOOTSTRAP_FILE))
    throw new \Exception("Bootstrap file missing. Please ensure to run 'composer update'.");
require GISMO_BOOTSTRAP_FILE;

ConfigLoader::FromFile(
    __DIR__ . "/../app.ini.dist",
    ConfigLoader::DEVELOPMENT,
    $config = new AppConfig()
);

AppLauncher::Get()->setConfig($config)->setApp(new YourApp($config));

