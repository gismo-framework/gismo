<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.08.16
 * Time: 00:45
 */

    namespace Gismo\Component\Partial;


    trait GoDiService_Partial {

        private function __di_init_service_partial() {

            $this[0] = function ($§§name) {

                if (class_exists($§§name)) {
                    $ref = new \ReflectionClass($§§name);
                    if ($ref->implementsInterface(Partial::class)) {
                        return $this->factory(function () use ($§§name) {
                            return new $§§name($this);
                        });
                    }
                }
            };
        }

    }