<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 04.08.17
 * Time: 10:07
 */

namespace Gismo\Component\Application\Yaml\Section;


use Gismo\Component\Application\Context;
use Gismo\Component\Di\Bindables\GoDiFile;
use Gismo\Component\Di\DiContainer;
use Gismo\Component\Di\DiSection;
use Gismo\Component\Partial\Page;
use Gismo\Component\Plugin\Plugin;
use MongoDB\Exception\InvalidArgumentException;

class DiSection_Plugins implements DiSection
{

    /**
     * Return the global section name
     *
     * @return string
     */
    public function getSectionName(): string
    {
        return "plugins";
    }

    /**
     * Called when DiContainer::load() is called
     *
     * @param string $sectionName
     * @param             $data
     * @param             $opt
     * @param DiContainer $container
     *
     * @return mixed
     */
    public function parse(string $sectionName, $data, $opt, DiContainer $container, GoDiFile $curFile) {
        if ( ! $container instanceof Context)
            throw new \InvalidArgumentException("Tpl parser requires container to be Context");

        foreach ($data as $class) {
            if ( ! class_exists($class))
                throw new InvalidArgumentException("Loading plugins: Class '$class' does not exist in '$curFile'");
            $plugin = new $class();
            if ( ! $plugin instanceof Plugin)
                throw new InvalidArgumentException("Plugin class '$class' must implement Plugin");
            $plugin->onContextInit($container);
        }

        return true;
    }

    public function validate(string $sectionName, $value)
    {
    }
}