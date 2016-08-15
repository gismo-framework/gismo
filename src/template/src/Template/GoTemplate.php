<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 23:49
     */

    namespace Gismo\Component\Template;

   
    use Gismo\Component\Template\Datastore\GoTemplateStore;
    use Gismo\Component\Template\Directive\GoDirective;
    use Gismo\Component\Template\Directive\GoDirectiveExecBag;
    use Gismo\Component\Template\Expression\GoExpressionEvaluator;
    use Gismo\Component\Template\Expression\Scope;

    class GoTemplate
    {

        /**
         * @var GoTemplateParser
         */
        private $mParser;


        /**
         * @var GoDirectiveExecBag
         */
        private $mExecBag;

        /**
         * @var GoTemplateStore|null
         */
        private $mTemplateStore = NULL;

        
        public function __construct()
        {
            $this->mParser = new GoTemplateParser();
            $this->mExecBag = new GoDirectiveExecBag(new GoExpressionEvaluator());
        }


        public function setTemplateStore (GoTemplateStore $store)
        {
            $this->mTemplateStore = $store;
        }

        public function getTemplateStore ()
        {
            return $this->mTemplateStore;
        }


        public function addDirective(GoDirective $directive)
        {
            $this->mParser->addDirective($directive);
        }

        public function getDirective(string $className) : GoDirective
        {
            return $this->mParser->getDirective($className);
        }

        public function getExpressionEvaluator () : GoExpressionEvaluator
        {
            return $this->mExecBag->expressionEvaluator;
        }

        public function setExpressionEvaluator(GoExpressionEvaluator $evaluator)
        {
            $this->mExecBag->expressionEvaluator = $evaluator;
        }


        public function setScopePrototype(array $scope)
        {
            $this->mExecBag->scopePrototype = $scope;
        }



        public function render(string $inputTemplateData, array $scopeData, &$structOutputData = []) : string
        {
            $scope = $this->mExecBag->scopePrototype;
            foreach ($scopeData as $key => $val) {
                $scope[$key] = $val;
            }


            $this->mParser->loadHtml($inputTemplateData);

            $template = $this->mParser->parse();

            return $template->run($scope, $this->mExecBag);
        }


        public function renderHtml($templateUri, array $scopeData) : string
        {
            if ($this->mTemplateStore === null)
                throw new \InvalidArgumentException("No template-store registered. (Resolving templateUri to Template disabled)");
            $templateData = $this->mTemplateStore->getTemplate($templateUri);
            return $this->render($templateData, $scopeData, $dummy);
        }

        public function renderHtmlFile($filename, array $scopeData = []) : string
        {
            return $this->render(file_get_contents($filename), $scopeData, $data);
        }
        
        

    }