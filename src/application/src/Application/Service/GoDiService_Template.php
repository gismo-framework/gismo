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
    use Gismo\Component\Di\Type\GoServiceDiDefinition;
    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\Partial\HtmlPartial;
    use Gismo\Component\Partial\NaviTree;
    use Gismo\Component\Partial\Page;
    use Html5\Template\Directive\GoCallDirective;
    use Html5\Template\Directive\GoExtendsDirective;
    use Html5\Template\Directive\GoNsCallDirective;
    use Html5\Template\HtmlTemplate;

    trait GoDiService_Template
    {
        private function __di_init_service_template() {
            $this[HtmlPartial::class] = $this->factory(function () {
                return new HtmlPartial($this);
            });

            $this[NaviTree::class] = $this->factory(function () {
                return new NaviTree();
            });

            $this[HtmlTemplate::class] = $this->service(function (Request $req) {
                $p = new HtmlTemplate();
                $p->getDirective(GoCallDirective::class)->setCallback(function ($name, $params) {
                    if (! is_array($params))
                        $params = [];

                    return $this[$name]($params);
                });
                $p->getDirective(GoNsCallDirective::class)->setCallback(function ($name, $params) {
                    if (! is_array($params))
                        $params = [];

                    return $this[$name]($params);
                });
                $p->getDirective(GoExtendsDirective::class)->setExtendsCallback(function ($name, $params) {
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
                        $params = [0  => $params];
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


        /**
         * Register a new Template
         *
         * <example>
         * $context["tpl.some.template"] = $context->template(__DIR__ . "/tpl/tpl.some.template.html");
         * </example>
         *
         * @param string $filename
         * @return GoServiceDiDefinition
         */
        public function template(string $filename) : GoServiceDiDefinition {
            return $this->service(
                    function () use ($filename) {
                        $page = new Page($this);
                        $page->setTemplate($filename);
                        return $page;
                    }
            );
        }


        /**
         * Register all Templates found in this path matching a pattern
         *
         * <example>
         * // This will add all Templates from /tpl
         * $context->addTemplatePath(__DIR__ . "/tpl");
         * </example>
         *
         *
         * @param string $path
         */
        public function addTemplatePath (string $path, string $pattern = "tpl.*.html") {
            $names = glob($path . "/" . $pattern);
            foreach ($names as $name) {
                if ( ! preg_match ("/^(?<bind>.+)(\\.html|\\.htm)$/i", basename($name), $matches)) {
                    throw new \InvalidArgumentException("Cannot extract bindName from fileName: '$name'. Must end with .html!");
                }

                $bind = $matches["bind"];
                $this[$bind] = $this->template($name);
            }
        }



    }