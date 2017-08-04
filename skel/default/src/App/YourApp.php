<?php

    
    namespace YourProject\App;
    use Gismo\Component\Config\AppConfig;
    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\Plugin\App;
    use Gismo\Component\Plugin\Loader\JsonFilePluginLoader;
    use Gismo\Component\Route\Type\RouterRequest;
    use Golafix\Conf\DotGolafixYml;
    use Golafix\Conf\GolafixRouter;
    use Golafix\Conf\ZipPool;

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 21.06.17
     * Time: 10:57
     */
    class YourApp implements App {
        
        /**
         * @var FrontendContext
         */
        private $mContext;

        public function __construct(AppConfig $config, $filenamePlugins) {
            $debug = false;
            if ($config->ENVIRONMENT === "DEVELOPMENT")
                $debug = true;
            $this->mContext = $c = new FrontendContext(true);
            $c->loadYaml(__DIR__ . "/../../frontend.yml");

            $pluginLoader = new JsonFilePluginLoader($this->mContext);
            $pluginLoader->initPluginsFromFile($filenamePlugins);

        }


        public function run(Request $request) {
            $p = $this->mContext;
            $p[Request::class] = $p->constant($request);

            $p->trigger("event.app.onrequest");
            $routeRequest = RouterRequest::BuildFromRequest($request);
            $p->route->dispatch($routeRequest);
        }
    }