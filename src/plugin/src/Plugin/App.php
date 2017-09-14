<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 16.08.16
     * Time: 10:25
     */


    namespace Gismo\Component\Plugin;



    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\Plugin;

    interface App {

        public function runCmd ();

        public function run(Request $request);

    }
