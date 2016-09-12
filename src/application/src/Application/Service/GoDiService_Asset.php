<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.08.16
 * Time: 00:45
 */

    namespace Gismo\Component\Application\Service;


    use Gismo\Component\Application\Assets\GoAssetContainer;
    use Gismo\Component\Application\Assets\GoAssetSet;
    use Gismo\Component\Application\Assets\GoAssetSetList;
    use Gismo\Component\Application\Assets\Handler\GoAssetHandler;
    use Gismo\Component\Application\Assets\Renderer\GoAssetRenderer;
    use Gismo\Component\Application\Assets\Renderer\GoCssAssetRenderer;
    use Gismo\Component\Application\Container\GoTemplate;
    use Gismo\Component\Di\Type\GoServiceDiDefinition;
    use Gismo\Component\Partial\Page;
    use Gismo\Component\Partial\Partial;
    use Html5\Template\Directive\GoCallDirective;
    use Html5\Template\HtmlTemplate;


    trait GoDiService_Asset {

        private function __di_init_service_asset() {
            $this->route->add("/assets/::path", function (array $path) {
                $forTemplate = array_shift($path);
                $tpl = $this[$forTemplate];
                if ( ! $tpl instanceof GoAssetContainer)
                    throw new \InvalidArgumentException("Bind name '$path' is no valid AssetContainer.");

                /* @var $tpl GoTemplate */
                $subPath = implode ("/", $path);
                header("Content-type: " . $tpl->getAssetContentType($subPath));
                echo $tpl->getAssetContent($subPath);
            });


            $this[HtmlTemplate::class] = $this->filter(function (HtmlTemplate $§§input) {
                $§§input->getExecBag()->expressionEvaluator->register("asset", function (array $arguments, $path) {
                    return $this->getAssetLinkUrl($path);
                });
            });

        }



        public function assetSet(string $rootDir, string $includeFilter="*.*", GoAssetRenderer $renderer) : GoServiceDiDefinition {
            return $this->service(function () use ($rootDir, $includeFilter, $renderer) {
                return (new GoAssetSet($rootDir, $this, $renderer))->include($includeFilter);
            });
        }

        /**
         * Define a AssetSetList
         *
         * <example>
         * $context["tpl.layout.assets.css"] = $context->aasetSetList(new GoCssAssetHandler());
         * </example>
         *
         * @param GoAssetHandler $handler
         * @return GoServiceDiDefinition
         */
        public function assetSetList(GoAssetHandler $handler) : GoServiceDiDefinition {
            return $this->service(function () use ($handler) {
                return new GoAssetSetList($this, $handler);
            });
        }


    }