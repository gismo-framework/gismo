<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.08.16
 * Time: 00:45
 */

    namespace Gismo\Component\Application\Service;


    use Gismo\Component\Application\Container\GoTemplate;
    use Gismo\Component\Partial\Page;
    use Gismo\Component\Partial\Partial;
    use Html5\Template\Directive\GoCallDirective;
    use Html5\Template\HtmlTemplate;


    trait GoDiService_Partial {

        private function __di_init_service_partial() {
            $this[HtmlTemplate::class] = $this->service(function () {
                $p = new HtmlTemplate();
                $p->getDirective(GoCallDirective::class)->setCallback(function ($name, $params) {
                    if (! is_array($params))
                        $params = [];
                    return $this[$name]($params);
                });
                return $p;
            });


            $this->route->add("/assets/::path", function (array $path) {
                $forTemplate = array_shift($path);
                $tpl = $this[$forTemplate];
                /* @var $tpl GoTemplate */
                header("Content-type: text/css");
                echo $tpl->getAsset($path);
            });

        }


        public function template($filename, $bindName) {
            return $this->service(function () use ($filename, $bindName) {
                return new GoTemplate($this, $filename, $bindName);
            });
        }



    }