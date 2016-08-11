<?php

/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 20:37
 */
    namespace Gismo\Test\Component;


    use Gismo\Component\Template\GoTemplate;

    class ParserTest extends \PHPUnit_Framework_TestCase
    {
    
    
        public function testGeneratedDocumentMatchesInputDocument()
        {
            $inputContent = file_get_contents(__DIR__ . "/mockfiles/testWhiteSpaceParsing.xml");
    
            $parser = new GoTemplate();
            $output = $parser->render($inputContent, []);
    
            $this->assertEquals($inputContent, $output);
        }
    
    
        public function testXmlToStruct()
        {
    
        }
    
    
    }
