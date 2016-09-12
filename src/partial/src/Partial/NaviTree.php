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

        public function __construct()
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
         * @return \stdClass
         */
        public function __invoke() {
            $meData = new \stdClass();
            $meData->name = $this->name;
            $meData->html = $this->html;
            $meData->link = $this->link;
            $meData->isLeaf = true;
            $meData->childs = null;

            $keys = $this->getDefinedKeys();
            if (count ($keys) > 0) {
                $meData->childs = [];
                $meData->isLeaf = false;
                foreach ($keys as $curKey) {
                    $meData->childs[] = $this[$curKey]->__invoke();
                }
            }
            return $meData;
        }

        /**
         * @param mixed $offset
         * @return NaviTree
         */
        public function offsetGet($offset)
        {
            return parent::offsetGet($offset);
        }


    }
