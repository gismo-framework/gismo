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

class DiSection_Route implements DiSection
{

    /**
     * Return the global section name
     *
     * @return string
     */
    public function getSectionName(): string
    {
        return "route";
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

        foreach ($data as $route => $what) {
            $container->route->add($route, function (array $params=[]) use ($what, $container, $curFile) {
                if (substr($what, 0, 1) === "@") {
                    $page = new Page($container);
                    $page->setTemplate($curFile->xpath(substr($what, 1)));
                    echo $page($params);
                } else {
                    echo $container[$what]();
                }
            });
        }

        return true;
    }

    public function validate(string $sectionName, $value)
    {
    }
}