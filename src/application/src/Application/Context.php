<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 18:38
     */    
    
    namespace Gismo\Component\Application;

    

    use Gismo\Component\Application\Service\GoDiService_Api;
    use Gismo\Component\Application\Service\GoDiService_App;
    use Gismo\Component\Application\Service\GoDiService_DevTools;
    use Gismo\Component\Application\Service\GoDiService_Partial;
    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\Route\GoDiService_Route;

    class Context extends DiContainer {
        use GoDiService_Route, GoDiService_Partial, GoDiService_Api, GoDiService_App, GoDiService_DevTools;
        


    }