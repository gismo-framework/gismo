<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 07.08.17
 * Time: 15:02
 */

namespace Gismo\Component\Application\Yaml\Section;


use Gismo\Component\Application\Context;
use Gismo\Component\Di\Bindables\GoDiFile;
use Gismo\Component\Di\DiContainer;
use Gismo\Component\Di\DiSection;
use MongoDB\Exception\InvalidArgumentException;

class DiSection_Extends implements DiSection
{

    /**
     * Return the global section name
     *
     * @return string
     */
    public function getSectionName(): string
    {
        return "extends";
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
    public function parse(
        string $sectionName,
        $data,
        $opt,
        DiContainer $container,
        GoDiFile $curFile
    ) {
        if ( ! is_array($data))
            throw new InvalidArgumentException("extends expects array of files.");
        if ( ! $container instanceof Context)
            throw new InvalidArgumentException("extends is only available on Application-Context");
        foreach ($data as $filename) {
            $file = $curFile->xpath($filename);
            try {
                $container->loadYaml($file);
            } catch (\Exception $e) {
                throw new \Exception("Excpetion while loading '$file' in '$curFile': {$e->getMessage()}", 0, $e);
            }
        }
    }

    public function validate(string $sectionName, $value)
    {

    }
}