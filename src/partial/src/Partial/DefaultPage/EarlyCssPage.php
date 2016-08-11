<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 05.08.16
 * Time: 22:44
 */

    namespace Gismo\Component\Partial\DefaultPage;

    use Gismo\Component\Partial\Page\AbstractPage;
    use Gismo\Component\Partial\Partial\AbstractListPartial;
    use Gismo\Component\Partial\Partial\SimpleListPartial;

    /**
     * Class MainCssPage
     * @package Gismo\Component\Partial\DefaultPage
     *          
     * @property AbstractListPartial $css;
     */
    class EarlyCssPage extends AbstractPage {

        public function __get_sections() : array {
            return ["css" => SimpleListPartial::class];
        }
        
        public function render($return = FALSE) : string {
            return $this->css->render($return);
        }
        
    }
