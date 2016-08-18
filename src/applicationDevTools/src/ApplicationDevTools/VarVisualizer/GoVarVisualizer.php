<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 19.01.15
 * Time: 11:41
 */


	namespace Gismo\Component\ApplicationDevTools\VarVisualizer;


    use Gismo\Component\PhpFoundation\Accessor\CallableAccessor;
    use Html5\Template\FHtml;

    class GoVarVisualizer {

        private static $elemCounter = 0;

		public function visualizeProp ($propName, $value, FHtml $tpl) {
			$printName = "<b>$propName</b>";
			$class = "string";

			if (is_string($value)) {
				$class = "string";
				$printName .= " = '" . htmlspecialchars($value) . "'";
			}
			if (is_object($value)) {
				$class = "object";
				$printName .= "<small> (". get_class($value) . ")</small>";
			}
			if (is_array($value)) {
				$class = "array";
				$printName .= "<small> [".count ($value). "]</small>";
			}
			if (is_integer($value) || is_double($value) || is_float($value)) {
				$printName .= " = $value";
			}


			$li = $tpl->elem("li");

			$id = "gvarvisualize__" . self::$elemCounter++;


			if (is_null($value)) {
				$printName .= " = <b>NULL</b>";
			}
			if (is_resource($value)) {
				$printName .= " = #Ressource";
			}
			if (is_callable($value)) {

				$ref = new CallableAccessor($value);
				$printName .= " (Closure: $ref)";
			}
			if ($value === TRUE)
				$printName .= " = TRUE";
			if ($value === FALSE)
				$printName .= " = FALSE";




			$li->elem("label @for=$id @class=$class")->text($printName)->end();
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