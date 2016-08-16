<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.08.16
 * Time: 00:45
 */

    namespace Gismo\Component\Partial;


    use Gismo\Component\Template\GoTemplate;

    trait GoDiService_Partial {

        private function __di_init_service_partial() {
            $this["partial.__PROTO__"] = $this->service(function ($§§name) {
                $p = new Partial($this);
                $p[0] = function () use ($§§name) {
                    return "No Template defined: $§§name";
                };
                return $p;
            });

            $this["page.__PROTO__"] = $this->service(function ($§§name) {
                $p = new Partial($this);
                $p[0] = function () use ($§§name) {
                    return "No Template defined: $§§name";
                };
                return $p;
            });

            $this[GoTemplate::class] = $this->service(function () {
                $template = new GoTemplate();
                return $template;
            });
        }


        public function templateFile($filename) {
            return function ($§§parameters, GoTemplate $template) use ($filename) {
                return $template->renderHtmlFile($filename, $§§parameters);
            };
        }



    }