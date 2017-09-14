<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 07.09.17
 * Time: 13:04
 */

namespace Gismo\Component\Plugin;


use Gismo\Component\Config\AppConfig;

class AppLauncher
{

    private static $sInstance = null;


    public static function Get() : self {
        if (self::$sInstance === null)
            self::$sInstance = new self();
        return self::$sInstance;
    }

    private $config;

    private $app;

    /**
     * @return mixed
     */
    public function getApp() : App
    {
        return $this->app;
    }

    /**
     * @param App $app
     *
     * @return AppLauncher
     * @internal param mixed $mApp
     *
     */
    public function setApp(App $app)
    {
        $this->app = $app;

        return $this;
    }

    public function setConfig (AppConfig $config)
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig () : AppConfig
    {
        return $this->config;
    }
 }