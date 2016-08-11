<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 06.08.16
     * Time: 18:18
     */


    namespace Gismo\Component\Partial\Partial;

    use Gismo\Component\Partial\Section\Section;

    class SimpleListPartial extends AbstractListPartial {


        public function render($return=false) : string {
            $ret = "";
        
            $this->mOrderedList->each(function (Section $what) use (&$ret, $return) {
                if ($return === true) {
                    $ret .= $what->render($return);
                } else {
                    $what->render($return);
                }
            });
        
            if ($return === true)
                return $ret;
            return null;
        }
    
    }