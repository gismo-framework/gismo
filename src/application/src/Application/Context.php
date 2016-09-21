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
    use Gismo\Component\Application\Service\GoDiService_Asset;
    use Gismo\Component\Application\Service\GoDiService_DevTools;
    use Gismo\Component\Application\Service\GoDiService_Event;
    use Gismo\Component\Application\Service\GoDiService_Partial;
    use Gismo\Component\Application\Service\GoDiService_Template;
    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\Route\GoDiService_Route;

    /**
     * Class Context
     * @package Gismo\Component\Application
     *
     * @property $debug bool
     */
    class Context extends DiContainer {
        use GoDiService_Route, GoDiService_Template, GoDiService_Asset, GoDiService_Api, GoDiService_App, GoDiService_DevTools,
            GoDiService_Event;
        

        public function __construct($debug=false, $assetRevision="0-0-0")
        {
            parent::__construct();
            $this->debug = $this->constant($debug)->protect("Debug value is set in Context and cannot be changed.");
            $this->assetRevision = $this->constant($assetRevision)->protect("Asset revision ist set in Context and cannot be changed.");
        }


    }