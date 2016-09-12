<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 22.08.16
     * Time: 18:58
     */
    namespace Gismo\Component\Application\Partial;



    use Gismo\Component\Application\Container\GoTemplate;
    use Gismo\Component\Application\Context;
    use Gismo\Component\Di\Core\Invokeable;
    use Gismo\Component\PhpFoundation\Type\OrderedList;

    class GoListPartial implements GoPartial, \ArrayAccess{


        private $mContext;
        private $mList;

        public function __construct(Context $context)
        {
            $this->mContext = $context;
            $this->mList = new OrderedList();
        }


        public function offsetExists($offset)
        {
            // TODO: Implement offsetExists() method.
        }


        public function offsetGet($offset)
        {
            // TODO: Implement offsetGet() method.
        }


        public function offsetSet($offset, $value)
        {
            if ( ! is_int($offset))
                throw new \InvalidArgumentException("Offset must be integer");

            if (is_string($value)) {
                if ( ! isset ($this->mContext[$value]))
                    throw new \InvalidArgumentException("No template registred with bindName '$value'");
                $this->mList->add($offset, $value);
                return;
            } else if ( ! is_callable($value))
                throw new \InvalidArgumentException("Value must be callable");
            $this->mList->add($offset, $value);
        }


        public function offsetUnset($offset)
        {
            // TODO: Implement offsetUnset() method.
        }

        public function __invoke($params=[])
        {
            $ret = "";
            $this->mList->each(function ($what) use (&$ret, $params) {
                if (is_string($what)) {
                    $tpl = $this->mContext[$what];
                    if ( ! $tpl instanceof GoTemplate)
                        throw new \InvalidArgumentException("BindName '$what' was expected to return GoTemplate. But '" . gettype($tpl) . "' was returned");
                    $ret .= $tpl($params);
                } else {

                    $ret .= ($this->mContext)($what, $params);
                }
            });
            return $ret;
        }
    }
