<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 10.08.16
 * Time: 23:07
 */

    require __DIR__ . "/../../vendor/autoload.php";

    ini_set("display_errors", 1);

    $logger = new \Gismo\Component\Logging\Logger\FirePhpLogger();

    $logger->info(["Some logging"]);