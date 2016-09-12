<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 16.08.16
     * Time: 09:47
     */

    namespace Gismo\Component\Partial;


    use Gismo\Component\Di\DiContainer;
    use Html5\Template\HtmlTemplate;

    class Page extends Partial {

        private $mTemplate;


        public function __construct(DiContainer $di)
        {
            parent::__construct($di, false);
        }


        public function setTemplate($filename) {
            $this->mTemplate = $filename;
        }


        public function __invoke($params = [])
        {
            $params = parent::__invoke($params);
            /* @var $parser HtmlTemplate */
            $parser = $this->mDi[HtmlTemplate::class];

            return $parser->renderHtmlFile($this->mTemplate, $params);
        }


    }