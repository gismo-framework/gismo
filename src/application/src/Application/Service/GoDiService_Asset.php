<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.08.16
 * Time: 00:45
 */

    namespace Gismo\Component\Application\Service;


    use gismo\Cache\Cache;
    use gismo\Cache\Driver\NoCacheDriver;
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

            $this["cache.frontend.asset"] = $this->service(function () {
                return new Cache(new NoCacheDriver(), $this, "cache.frontend.asset", 0);
            });

            $this->route->add("/assets/::path", function (array $path) {
                $forTemplate = array_shift($path);
                $tpl = $this[$forTemplate];
                if ( ! $tpl instanceof GoAssetContainer)
                    throw new \InvalidArgumentException("Bind name '$path' is no valid AssetContainer.");

                $cache = $this["cache.frontend.asset"];
                if ( ! $cache instanceof Cache)
                    throw new \InvalidArgumentException("cache.fontend.asset must be instance of Cache");


                /* @var $tpl GoTemplate */
                $subPath = implode ("/", $path);

                $assetData = $cache(function () use ($tpl, $subPath) {
                    return [
                        0 => $tpl->getAssetContentType($subPath),
                        1 => $tpl->getAssetContent($subPath)

                    ];
                } , ["path" => $path]);

                header("Content-Type: {$assetData[0]};charset=utf-8");
                echo $assetData[1];
            });


            $this[HtmlTemplate::class] = $this->filter(function (HtmlTemplate $§§input) {
                $§§input->getExecBag()->expressionEvaluator->register("asset", function (array $arguments, $path) {
                    $asset = $this[$path[0]];
                    if ( ! $asset instanceof GoAssetContainer)
                        throw new \Exception("asset($path[0],...): no GoAssetContainer");
                    return $asset->getAssetLinkUrl($path);
                });
            });

        }


        /**
         * @param string $rootDir
         * @param string|string[] $includeFilter
         * @param GoAssetRenderer $renderer
         * @return GoServiceDiDefinition
         */
        public function assetSet(string $rootDir, $includeFilter="*.*") : GoServiceDiDefinition {
            return $this->service(function () use ($rootDir, $includeFilter) {
                return (new GoAssetSet($rootDir, $this))->include($includeFilter);
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