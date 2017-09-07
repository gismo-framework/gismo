<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 18:12
     */

    namespace Gismo\Component\Application\Service;

    use Phore\Cli\CliController;
    use Phore\Cli\CliGroup;


    /**
     * Class GoDiService_Api
     * @package Gismo\Component\Api
     *
     */
    trait GoDiService_Cli {

        private function __di_init_service_cli() {
            $this["cli.controller"] = $this->service(function () {
                $ctrl = new CliController();
                $ctrl->setDiContainer($this);
                return $ctrl;
            });
        }


        public function cligroup (string $name) : CliGroup {
            $container = $this["cli.controller"];
            /* @var $container \Phore\Cli\CliController */
            return $container->group($name);
        }

    }