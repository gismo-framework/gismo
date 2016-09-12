<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.09.16
     * Time: 09:56
     */


    namespace Gismo\Component\Partial;


    use Gismo\Component\PhpFoundation\Type\PrototypeMap;

    class NaviTree extends PrototypeMap {

        public function __construct($prototype)
        {
            parent::__construct($this);
        }


        /**
         * @var string
         */
        public $name;

        /**
         * @var string
         */
        public $html;

        /**
         * @var string
         */
        public $link;


        /**
         * @return array
         */
        public function __invoke() {
            $meData = [
                "name" => $this->name,
                "html" => $this->html,
                "link" => $this->link,
                "isLeaf" => true,
                "childs" => null
            ];

            $keys = $this->getDefinedKeys();
            if (count ($keys) > 0) {
                $meData["childs"] = [];
                $meData["isLeaf"] = false;
                foreach ($keys as $curKey) {
                    $meData["childs"][] = $this[$curKey]->__invoke();
                }
            }
            return $meData;
        }


    }
