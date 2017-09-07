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
    use Phore\Cli\CliController;

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

        public function __construct(AppConfig $config) {
            $debug = false;
            if ($config->ENVIRONMENT === "DEVELOPMENT")
                $debug = true;
            $this->mContext = $c = new FrontendContext(true);
            $c->loadYaml(__DIR__ . "/../../frontend.yml");
            $plugin = new BasePlugin();
            $plugin->onContextInit($c);
        }


        public function runCmd (array $mockParams=null) {
            $context = $this->mContext;

            $ctrl = $context["cli.controller"];
            if ( ! $ctrl instanceof CliController)
                throw new \InvalidArgumentException("'cli.controller' should be of Type CliController");
            $ctrl->dispatch($mockParams);
        }

        public function run(Request $request) {
            $p = $this->mContext;
            $p[Request::class] = $p->constant($request);

            $p->trigger("event.app.onrequest");
            $routeRequest = RouterRequest::BuildFromRequest($request);
            $p->route->dispatch($routeRequest);
        }
    }