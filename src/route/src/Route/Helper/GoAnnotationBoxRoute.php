<?php

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:24
     */

    namespace Gismo\Component\Route\Helper;


    use Gismo\Component\Annotation\GoAnnotationPack;
    use Gismo\Component\Route\Annotation\Api;
    use Gismo\Component\Route\Annotation\Filter;
    use Gismo\Component\Route\Annotation\Mount;
    use Gismo\Component\Route\Annotation\Parameter;
    use Gismo\Component\Route\Annotation\Parameters;
    use Gismo\Component\Route\Annotation\Requires;
    use Gismo\Component\Route\Annotation\Route;

    class GoAnnoationPack_Route implements GoAnnotationPack {


        /**
         * Return a Array of Annotation Classnames to be loaded
         *
         * @return string[]
         */
        public function getAnnotationClassNames() {
            return [
                Mount::class,
                Route::class,
                Api::class,
                Filter::class,
                Parameters::class,
                Parameter::class,
                Parameters::class,
                Requires::class
            ];
        }
    }