<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 18:12
     */

    namespace Gismo\Component\Application\Service;
    use Gismo\Component\Application\Context;
    use Gismo\Component\ApplicationDevTools\VarVisualizer\GoVarVisualizer;
    use Gismo\Component\Di\DiCallChain;
    use Gismo\Component\PhpFoundation\Helper\StopWatch;


    /**
     * Class GoDiService_Api
     * @package Gismo\Component\Api
     *
     */
    trait GoDiService_DevTools {

        private function __di_init_service_devtools() {
            $sw = new StopWatch();

            $this->route->add("/devTools/status", function (Context $context) use ($sw) {
                $timing = [];
                $timing["[Time to routing call]"] = number_format($sw->lap(), 4);
                $varVisualizer = new GoVarVisualizer();
                $varVisualizer->outputVisualisation($context->__debug_getDiDef(), "Pre Run Environment (filter-only)");


                $con = [];
                $timing["[Time to finish prepare stage]"] = number_format($sw->lap(), 4);
                $conSw = new StopWatch();
                foreach ($context->__debug_getDiDef()->getDefinedKeys() as $name) {
                    $con[$name] = $context[$name];
                    $timing[$name] = number_format($conSw->lap(), 4);
                }
                $varVisualizer->outputVisualisation($con, "Run Environment");

                $timing["[Time total]"] = number_format($sw->total(), 4);

                $varVisualizer->outputVisualisation($timing, "Benchmark times[sec]");

            });
        }

    }