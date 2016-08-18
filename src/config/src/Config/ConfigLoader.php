<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 19.08.16
 * Time: 00:06
 */

    namespace Gismo\Component\Config;


    class ConfigLoader {

        const PRODUCTION = "PRODUCTION";
        const DEVELOPMENT = "DEVELOPMENT";
        const TESTING = "TESTING";


        final private function __construct ($filename) {

        }


        public static function FromFile($filename, $env, AppConfig $config) : self {
            $data = parse_ini_file($filename, true);
            $guessedEnvironment = $env;

            if ( ! isset ($data[$guessedEnvironment]))
                throw new \InvalidArgumentException("Section '$guessedEnvironment' missing in config file '$filename'. (Section '[$guessedEnvironment]' missing)");
            $realData = $data[$guessedEnvironment];

            foreach ($config as $key => $defaultValue) {
                if (substr($key, 0, 2) == "__")
                    continue;
                if (!isset ($realData[$key])) {
                    if (!empty($defaultValue))
                        continue;
                    throw new \InvalidArgumentException("Section [$guessedEnvironment] is missing configuration-directive '$key' in config file '$filename'");
                }
                $value = $realData[$key];
                if (is_string($value)) {
                    // Replace reference to ENV
                    $value = preg_replace_callback("/%([a-zA-Z0-9_\\.]+)%/", function ($matches) use ($guessedEnvironment, $filename) {
                        $envName = $matches[1];
                        $value = getenv($envName);
                        if ($value === false)
                            throw new \InvalidArgumentException("Required environment variable '$matches[0]' not set. Defined in section '[$guessedEnvironment]' of config-file '$filename'");
                        return $value;
                    }, $value);
                }
                $config->$key = $value;
            }
            return true;
        }



    }