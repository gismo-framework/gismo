<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 19.08.16
     * Time: 14:47
     */

    namespace Gismo\Component\Application\Container;


    use Gismo\Component\Di\DiCallChain;

    class GoApi extends DiCallChain implements GoLinkable {

        /**
         * @var null|string
         */
        private $mAssociatedRoute = null;

        public function __setAssociatedRouteBindName(string $bindName = null) {
            $this->mAssociatedRoute = $bindName;
        }


        public function link($params=[], $getParams=null) : string{
            if ($this->mAssociatedRoute === null)
                throw new \InvalidArgumentException("Api has not route association.");
            return $this->mDi[$this->mAssociatedRoute]->link($params, $getParams);
        }

        public function linkAbs($params=[], $getParams=null) : string {
            if ($this->mAssociatedRoute === null)
                throw new \InvalidArgumentException("Api has not route association.");
            return $this->mDi[$this->mAssociatedRoute]->linkAbs($params, $getParams);
        }

    }