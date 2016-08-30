<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 30.08.16
     * Time: 10:52
     */

    namespace Gismo\Component\Application\Container;


    use Gismo\Component\Application\Context;

    class GoDeferBind
    {

        private $mContext;
        private $mBindNameToCall;

        public function __construct(Context $context, string $bindNameToCall)
        {
            $this->mContext = $context;
            $this->mBindNameToCall = $bindNameToCall;
        }


        public function __toString()
        {
            return "[DeferBind:{$this->mBindNameToCall}]";
        }

        public function __invoke($§§parameters)
        {
            return $this->mContext[$this->mBindNameToCall]($§§parameters);
        }

    }