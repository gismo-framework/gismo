<?php

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:24
     */

    namespace Gismo\Component\Application\Builder;




    use Gismo\Component\Application\Builder\Annotation\App\Action;
    use Gismo\Component\Application\Builder\Annotation\App\AllowAll;
    use Gismo\Component\Application\Builder\Annotation\App\Api;
    use Gismo\Component\Application\Builder\Annotation\App\ContextInit;
    use Gismo\Component\Application\Builder\Annotation\App\Filter;
    use Gismo\Component\Application\Builder\Annotation\App\Mount;
    use Gismo\Component\Application\Builder\Annotation\App\Parameter;
    use Gismo\Component\Application\Builder\Annotation\App\Parameters;
    use Gismo\Component\Application\Builder\Annotation\App\Requires;
    use Gismo\Component\Application\Builder\Annotation\App\Route;
    use Phore\Annotations\AnnotationPack;

    class GoAnnotationPack_Application implements AnnotationPack {


        /**
         * Return a Array of Annotation Classnames to be loaded
         *
         * @return string[]
         */
        public function getAnnotationClassNames() {
            return [
                Action::class,
                AllowAll::class,
                Api::class,
                Filter::class,
                Mount::class,
                Parameter::class,
                Requires::class,
                Route::class,
                ContextInit::class
            ];
        }
    }