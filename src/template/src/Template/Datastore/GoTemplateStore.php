<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 13:32
     */

    namespace Gismo\Component\Template\Datastore;


    interface GoTemplateStore {

        public function hasTemplate(string $locator) : bool;

        public function getTemplate(string $locator) : string;

    }