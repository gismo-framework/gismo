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

class DiSection_Tpl implements DiSection
{

    /**
     * Return the global section name
     *
     * @return string
     */
    public function getSectionName(): string
    {
        return "tpl";
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
        if ( ! is_string($data))
            throw new \InvalidArgumentException("bind accepts only one string argument.");
        if ( ! $container instanceof Context)
            throw new \InvalidArgumentException("Tpl parser requires container to be Context");

        $container[$sectionName] = $container->template($curFile->xpath($data));
        return true;
    }

    public function validate(string $sectionName, $value)
    {
    }
}