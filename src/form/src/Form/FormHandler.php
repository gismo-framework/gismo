<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.08.16
 * Time: 23:22
 */

    namespace Gismo\Component\Form;


    interface FormHandler {

        public function load(FormData $f);

        public function validate(FormData $f);

        public function store(FormData $f);

    }
