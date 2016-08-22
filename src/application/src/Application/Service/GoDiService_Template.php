<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 22.08.16
     * Time: 18:04
     */

    namespace Gismo\Component\Application\Service;


    use Gismo\Component\Application\Assets\GoAssetContainer;
    use Gismo\Component\Application\Container\GoTemplate;
    use Gismo\Component\Application\Partial\GoListPartial;
    use Gismo\Component\Application\Partial\GoPartial;
    use Html5\Template\Directive\GoCallDirective;
    use Html5\Template\HtmlTemplate;

    trait GoDiService_Template
    {
        private function __di_init_service_template() {
            $this[HtmlTemplate::class] = $this->service(function () {
                $p = new HtmlTemplate();
                $p->getDirective(GoCallDirective::class)->setCallback(function ($name, $params) {
                    if (! is_array($params))
                        $params = [];
                    return $this[$name]($params);
                });
                return $p;
            });
        }



        public function usePartial(string $bindName, string $className = GoListPartial::class) : self {
            if ( ! in_array(GoPartial::class, class_implements($className)))
                throw new \InvalidArgumentException("usePartial(): Parameter 2 must be valid ClassName implementing GoPartial. Found '$className'");
            $this[$bindName] = $this->service(function () use ($className) {
                return new $className($this);
            });
            return $this;
        }



        public function useTemplate(string $bindName, string $filename) : self {
            $this[$bindName] = $this->service(function () use ($filename, $bindName) {
                return new GoTemplate($this, $filename, $bindName);
            });
            return $this;
        }
    }