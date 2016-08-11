<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 04.08.16
 * Time: 21:49
 */


    use Gismo\Component\Application\Api\ApiContainer;

    /**
     * Class ShopContext
     *
     * 
     */
    class ShopContext extends \Gismo\Component\Di\DiContainer {
        use \Gismo\Component\Application\Api\ApiTrait;
    }



    $c = new ShopContext();

    