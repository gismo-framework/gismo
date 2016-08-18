<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 18.08.16
     * Time: 13:15
     */


    require __DIR__ . "/../../../../../vendor/autoload.php";


    $varVisualizer = new \Gismo\Component\ApplicationDevTools\VarVisualizer\GoVarVisualizer();
    $varVisualizer->outputVisualisation(["data"=>["Some other struct"], "obj" => new stdClass()]);