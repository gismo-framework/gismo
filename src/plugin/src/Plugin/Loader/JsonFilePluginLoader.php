<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 23.08.16
     * Time: 12:48
     */

    namespace Gismo\Component\Plugin\Loader;


    use Gismo\Component\Application\Context;
    use Gismo\Component\Plugin\Plugin;

    class JsonFilePluginLoader {

        /**
         * @var Context
         */
        private $context;


        public function __construct(Context $context)
        {
            $this->context = $context;
        }


        public function initPluginsFromFile($filename) {
            $data = json_decode(file_get_contents($filename), true);
            if ( ! is_array($data))
                throw new \InvalidArgumentException("Cannot parse '$filename'");
            foreach ($data["plugins"] as $pluginName) {
                $plugin = new $pluginName();
                if ( ! $plugin instanceof Plugin)
                    throw new \InvalidArgumentException("Plugin '$pluginName' defined in '$filename' must be instance of Plugin");
                try {
                    $plugin->onContextInit($this->context);
                } catch (\Exception $e) {
                    throw new \Exception("Exception during plugin init: Plugin: '$pluginName'", 0, $e);
                }
            }
        }

    }