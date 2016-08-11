<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 20:26
 */

    namespace Gismo\Component\Template;
    
    use Gismo\Component\Template\Directive\GoBindDirective;
    use Gismo\Component\Template\Directive\GoCallMacroDirective;
    use Gismo\Component\Template\Directive\GoClassDirective;
    use Gismo\Component\Template\Directive\GoDirective;
    use Gismo\Component\Template\Directive\GoDumpDirective;
    use Gismo\Component\Template\Directive\GoForeachDirective;
    use Gismo\Component\Template\Directive\GoIfDirective;
    use Gismo\Component\Template\Directive\GoInlineTextDirective;
    use Gismo\Component\Template\Directive\GoMacroDirective;
    use Gismo\Component\Template\Directive\GoRepeatDirective;
    use Gismo\Component\Template\Node\GoCommentNode;
    use Gismo\Component\Template\Node\GoDocumentNode;
    use Gismo\Component\Template\Node\GoElementNode;
    use Gismo\Component\Template\Node\GoTextNode;

    class GoTemplateParser
    {


        /**
         * @var GoTemplateDirectiveBag
         */
        private $directiveBag;

        
        public function __construct()
        {
            $this->directiveBag = new GoTemplateDirectiveBag();

            $this->addDirective(new GoIfDirective());
            $this->addDirective(new GoForeachDirective());
            $this->addDirective(new GoBindDirective());
            $this->addDirective(new GoClassDirective());
            $this->addDirective(new GoRepeatDirective());
            $this->addDirective(new GoMacroDirective());
            $this->addDirective(new GoCallMacroDirective());
            $this->addDirective(new GoDumpDirective());
            $this->addDirective(new GoInlineTextDirective());

        }


        public function getDirective(string $className) : GoDirective
        {
            return $this->directiveBag->directiveClassNameMap[$className];
        }

        public function addDirective(GoDirective $d)
        {
            $d->register($this->directiveBag);
        }


        /**
         * @var \XMLReader
         */
        private $loadedXmlReader = null;


        public function loadHtml($input)
        {
            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($input);
            libxml_use_internal_errors(false);
            $xhtml = $doc->saveXML();

            $this->loadXml($xhtml);
        }


        public function loadXml($input)
        {
            $reader = new \XMLReader();
            $reader->XML($input);
            $this->loadedXmlReader = $reader;
        }
        
        public function loadXmlFile($filename)
        {
            $this->loadXml(file_get_contents($filename));
        }


        public function parse() : GoDocumentNode
        {
            if ($this->loadedXmlReader === null)
                throw new \InvalidArgumentException("No document loaded. Call loadXml() or loadHtml() first.");

            $reader = $this->loadedXmlReader;

            $rootNode = new GoDocumentNode();
            $curNode = $rootNode;

            $curSignificantWhiteSpace = "";
            $curLine = 1;
            while ($reader->read()) {
                
                switch ($reader->nodeType) {
                    case \XMLReader::ELEMENT:
                        $newNode = new GoElementNode();
                        $newNode->name = $reader->name;
                        $newNode->lineNo = $curLine;

                        $newNode->isEmptyElement = $reader->isEmptyElement;

                        if (isset ($this->directiveBag->elemToDirective[$newNode->name])) {
                            $newNode->useDirective($this->directiveBag->elemToDirective[$newNode->name]);
                        }

                        $newNode->preWhiteSpace = $curSignificantWhiteSpace;
                        $curSignificantWhiteSpace = "";
                        $newNode->parent = $curNode;

                        if ($reader->hasAttributes) {
                            while ($reader->moveToNextAttribute()) {
                                if (isset ($this->directiveBag->attrToDirective[$reader->name])) {
                                    $newNode->useDirective($this->directiveBag->attrToDirective[$reader->name]);
                                }
                                $newNode->attributes[$reader->name] = $reader->value;
                            }
                        }

                        $newNode->postInit();

                        $curNode->childs[] = $newNode;
                        if ( ! $newNode->isEmptyElement) {
                            $curNode = $newNode;
                        }
                        break;

                    case \XMLReader::WHITESPACE:
                        $curLine += substr_count($reader->value, "\n");
                        $curSignificantWhiteSpace .= $reader->value;
                        //$curNode->childs[] = new PhbeamWhiteSpaceNode($reader->value);
                        break;
                    
                    case \XMLReader::SIGNIFICANT_WHITESPACE:
                        $curLine += substr_count($reader->value, "\n");
                        $curSignificantWhiteSpace .= $reader->value;
                        break;



                    case \XMLReader::COMMENT:
                        $curLine += substr_count($reader->value, "\n");
                        $curNode->childs[] = $newChild = new GoCommentNode($reader->value);
                        $newChild->preWhiteSpace = $curSignificantWhiteSpace;
                        $curSignificantWhiteSpace = "";
                        break;

                    case \XMLReader::END_ELEMENT:
                        $curNode->postWhiteSpace = $curSignificantWhiteSpace;
                        $curSignificantWhiteSpace = "";
                        $curNode = $curNode->parent;

                        break;

                    case \XMLReader::TEXT:
                        $curLine += substr_count($reader->value, "\n");
                        
                        
                        
                        $text = new GoTextNode($reader->value, $this->directiveBag->textDirective);
                        $text->preWhiteSpace = $curSignificantWhiteSpace;
                        $curSignificantWhiteSpace = "";
                        $curNode->childs[] = $text;
                        break;

                }
            }

            return $rootNode;
        }
    }