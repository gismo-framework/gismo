<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 03.08.17
 * Time: 16:14
 */

namespace Gismo\Component\Di;


use Gismo\Component\Di\Bindables\GoDiFile;

interface DiSection
{

    /**
     * Return the global section name
     *
     * @return string
     */
    public function getSectionName() : string;

    /**
     * Called when DiContainer::load() is called
     *
     * @param string      $sectionName
     * @param             $data
     * @param             $opt
     * @param DiContainer $container
     *
     * @return mixed
     */
    public function parse (string $sectionName, $data, $opt, DiContainer $container, GoDiFile $curFile);

    public function validate (string $sectionName, $value);
}