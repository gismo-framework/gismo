<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 19:51
     */
    
    
    namespace Gismo\Component\Template\Directive;


   

    use Gismo\Component\Template\Directive\Ex\GoBreakLoopException;
    use Gismo\Component\Template\Directive\Ex\GoContinueLoopException;
    use Gismo\Component\Template\Node\GoElementNode;
    use Gismo\Component\Template\GoTemplateDirectiveBag;

    class GoRepeatDirective implements GoDirective
    {
        
        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->attrToDirective["go-repeat"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int {
            return 50;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag)
        {
            $stmt = $node->attributes["go-repeat"];

            if (preg_match ('/^(.*)\s+index\s+([a-z0-9_]+)$/i', trim ($stmt), $matches)) {
                $data = $execBag->expressionEvaluator->eval($matches[1], $scope);
                for ($i = 0; $i < $data; $i++) {

                    $scope[$matches[2]] = $i;
                    $clone = clone $node;
                    $clone->attributes["go-repeat"] = "";
                    try {
                        $output .= $clone->render($scope, $execBag);
                    } catch (GoBreakLoopException $e) {
                        break;
                    } catch (GoContinueLoopException $e) {
                        continue;
                    }
                }
                return TRUE;

            } else {
                throw new \InvalidArgumentException("Cannot parse repeat '$stmt'");
            }
        }
    }