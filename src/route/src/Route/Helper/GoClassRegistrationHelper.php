<?php

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:15
     */


    namespace Gismo\Component\Route\Helper;



    use Gismo\Component\Annotation\GoAnnotations;
    use Gismo\Component\Application\Context;

    class GoClassRegistrationHelper {


        /**
         * @var Context
         */
        private $mContext;


        public function __construct(Context $context) {
            $this->mContext = $context;
            GoAnnotations::Require(GoAnnoationPack_Route::class);
        }


        public function register (\stdClass $subject) {
            $ref = new \ReflectionObject($subject);




        }



    }