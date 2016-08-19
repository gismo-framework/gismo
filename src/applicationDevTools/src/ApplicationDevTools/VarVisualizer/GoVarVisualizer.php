<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 19.01.15
 * Time: 11:41
 */


	namespace Gismo\Component\ApplicationDevTools\VarVisualizer;


    use Gismo\Component\PhpFoundation\Accessor\CallableAccessor;
    use Html5\FHtml\FHtml;


    class GoVarVisualizer {

        private static $elemCounter = 0;

		public function visualizeProp ($propName, $value, FHtml $tpl) {
			$printName = "<b>$propName</b>";
			$class = "string";

            $li = $tpl->elem("li");
            $id = "gvarvisualize__" . self::$elemCounter++;




			if (is_string($value)) {
			    $li->elem("label @for=$id @class=string")
                        ->elem("b")->text("$propName")->end()
                        ->elem("small")->text("string = '$value'");
			}
			if (is_object($value)) {
			    $li->elem("label @for=$id @class=object")
                        ->elem("b")->text("$propName")->end()
                        ->elem("small")->text("(". get_class($value) . ")");
			}
			if (is_array($value) && ! is_callable($value)) {
			    $li->elem("label @for=$id @class=array")
                        ->elem("b")->text("$propName")->end()
                        ->elem("small")->text("[".count ($value). "]");

			}
			if (is_integer($value) || is_double($value) || is_float($value)) {
			    $li->elem("label @for=$id @class=string")
                        ->elem("b")->text("$propName")->end()
                        ->elem("small")->text("= $value");

			}

			if (is_null($value)) {
			    $li->elem("label @for=$id @class=string")
                        ->elem("b")->text("$propName")->end()
                        ->elem("small")->text("= NULL");

			}
			if (is_resource($value)) {
                $li->elem("label @for=$id @class=string")
                   ->elem("b")->text("$propName")->end()
                   ->elem("small")->text("= #Ressource");

			}
			if (is_callable($value) && ! is_object($value)) {
                $li->elem("label @for=$id @class=string")
                   ->elem("b")->text("$propName")->end()
                   ->elem("small")->text("= (Closure: " . (new CallableAccessor($value)) . ")");

			}
			if ($value === TRUE) {
                $li->elem("label @for=$id @class=string")
                   ->elem("b")->text("$propName")->end()
                   ->elem("small")->text("= TRUE");
            }
			if ($value === FALSE) {
                $li->elem("label @for=$id @class=string")
                   ->elem("b")->text("$propName")->end()
                   ->elem("small")->text("= FALSE");
            }

			$li->elem("input @id= $id @type=checkbox @name=$id")->end();

			if (is_object($value) || is_array($value)) {
			    $ul = $li->elem("ul");

                if (is_object($value)) {
                    if (method_exists($value, "__debugInfo"))
                        $value = $value->__debugInfo();
                }
				foreach ($value as $key => $val) {
					$this->visualizeProp($key, $val, $ul);
				}
			}
		}


		public function getCss () {
			return file_get_contents(__DIR__ . "/varVisualizerTree.css");
		}


		public function visualize ($data, FHtml $tpl = null) {
		    if ($tpl === null)
		        $tpl = new FHtml();
            $mainDiv = $tpl->elem("div @class = gismo_varVisualizer");

            if (is_object($data)) {
                if (method_exists($data, "__debugInfo"))
                    $data = $data->__debugInfo();
            }
			foreach ($data as $key=>$value)
				$this->visualizeProp($key, $value, $mainDiv);
			return $tpl->render();
		}


		/**
		 * Erzeugt eine Seite mit der Visualisierung
		 *
		 * @param $data
		 * @param string $headerText
		 */
		public function outputVisualisation ($data, $headerText="Default Visualisation") {
		    $tpl = new FHtml();
            $tpl->elem("meta @charset = UTF-8")->end()
                ->elem("h1")->text($headerText)->end()
                ->elem("style @type = text/css")->text($this->getCss())->end();

			echo $this->visualize($data, $tpl);
		}


	}