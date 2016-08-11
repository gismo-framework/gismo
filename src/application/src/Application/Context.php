<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 18:38
     */    
    
    namespace Gismo\Component\Application;

    

    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\Route\GoDiService_Route;

    class Context extends DiContainer {
        use GoDiService_Route;
        


    }