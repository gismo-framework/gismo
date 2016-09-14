<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 22.08.16
     * Time: 18:04
     */

    namespace Gismo\Component\Application\Service;


    use Gismo\Component\Application\Assets\GoAssetContainer;
    use Gismo\Component\Application\Container\GoLinkable;
    use Gismo\Component\Application\Container\GoTemplate;
    use Gismo\Component\Application\Partial\GoListPartial;
    use Gismo\Component\Application\Partial\GoPartial;
    use Gismo\Component\Di\Ex\NoFactoryException;
    use Gismo\Component\HttpFoundation\Request\Request;
    use Html5\Template\Directive\GoCallDirective;
    use Html5\Template\HtmlTemplate;

    trait GoDiService_Template
    {
        private function __di_init_service_template() {
            $this[HtmlTemplate::class] = $this->service(function (Request $req) {
                $p = new HtmlTemplate();
                $p->getDirective(GoCallDirective::class)->setCallback(function ($name, $params) {
                    if (! is_array($params))
                        $params = [];

                    return $this[$name]($params);
                });


                /**
                 * the asset() method: is defined in the GoTemplate itself.
                 */

                /**
                 * The link() Method:
                 *
                 * Usage:
                 * link("/some/route")
                 * link("/some", varName)
                 * link("some.action", param1, param2)
                 * link({"some.action", param1, param2}, {"getParam1": "getValue1"})
                 *
                 */
                $p->getExecBag()->expressionEvaluator->register("link", function ($w, ...$params) use ($req) {

                    if (count($params) == 0) {
                        return $req->ROUTE_START_PATH;
                    }
                    if (is_string($params[0])) {
                        $params = [0 => [0 => $params]];
                    }

                    if (substr($params[0][0], 0, 1) === "/") {
                        // Normale Route
                        $params[0][0] = substr($params[0][0], 1); // strip "/"
                        for ($i = 0; $i < count($params[0]); $i++) {
                            $params[0][$i] = urlencode(urlencode($params[0][$i]));
                        }
                        $route = $req->ROUTE_START_PATH . "/" . implode("/", $params[0]);

                        if (isset ($params[1]))
                            $route .= "?" . http_build_query($params[1]);
                        return $route;

                    } else {
                        try {
                            $res = $this[$params[0][0]];
                        } catch (\Exception $e) {
                            throw new \InvalidArgumentException("link({$params[0]}): Exception while loading ressource: {$e->getMessage()}",
                                    0, $e);
                        }
                        if ( ! $res instanceof GoLinkable) {
                            throw new \InvalidArgumentException("link({$params[0]}) is expected to be name of action, api or route");
                        }
                        array_shift($params[0]);

                        $getParams = 0;
                        if (isset ($params[1]))
                            $getParams = $params[1];

                        $route = $res->link($params, $getParams);
                    }
                    return $route;

                });
                return $p;
            });
        }



        public function definePartial(string $bindName, string $className = GoListPartial::class) : self {
            if ( ! in_array(GoPartial::class, class_implements($className)))
                throw new \InvalidArgumentException("usePartial(): Parameter 2 must be valid ClassName implementing GoPartial. Found '$className'");
            $this[$bindName] = $this->service(function () use ($className) {
                return new $className($this);
            });
            return $this;
        }



        public function defineTemplate(string $bindName, string $filename) : self {
            $this[$bindName] = $this->service(function () use ($filename, $bindName) {
                return new GoTemplate($this, $filename, $bindName);
            });
            return $this;
        }
    }