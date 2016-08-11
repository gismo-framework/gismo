<?php

    
    namespace Gismo\Test\Component;
    use Gismo\Component\PhpFoundation\Type\OrderedList;



    class OrderedListTest extends \PHPUnit_Framework_TestCase {

        
        public function testOrder () {
            $list = new OrderedList();
            
            $list->add(100, "A");
            $list->add(200, "B");
            $list->add(-100, "C");
            
            $str = "";
            $list->each(function ($what, $prio, $alias) use (&$str) {
                $str .= $what; 
            });
            
            $this->assertEquals("BAC", $str);
            
        }
        
        
    }
