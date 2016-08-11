<?php

/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 10.08.16
 * Time: 00:44
 */

namespace Gismo\Test\Component\Route;

class RouteSerializeTest extends \PHPUnit_Framework_TestCase
{

    public function testJsonEncode() {
        echo json_encode(["some"=>"value"]);
    }

}
