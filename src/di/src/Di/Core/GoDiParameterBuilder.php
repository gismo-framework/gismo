<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 28.07.16
     * Time: 02:38
     */


    namespace Gismo\Component\Di\Core;

   
    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\Di\Ex\NoFactoryException;
    use Gismo\Component\Di\Type\DiClosureFactory;
    use Gismo\Component\Di\Type\DiFactory;
    use Gismo\Component\Di\Type\GoAbstractDiDefinition;
    use Gismo\Component\Di\Type\GoConstantDiDefinition;
    use Gismo\Component\Di\Type\GoFactoryDiDefinition;

    class GoDiParameterBuilder {
        /**
         * @var DiContainer
         */
        private $mDi;

        public function __construct(DiContainer $container) {
            $this->mDi = $container;
        }


        /**
         * @var GoAbstractDiDefinition[]
         */
        private $override = [];


        public function override($injectableName, $valueOrFactory) : self {

            if ($valueOrFactory instanceof GoAbstractDiDefinition) {
                $this->override[$injectableName] = $valueOrFactory;
                return $this;
            }
            if (is_callable($valueOrFactory)) {
                $valueOrFactory = new GoFactoryDiDefinition($valueOrFactory);
            } else {
                $valueOrFactory = new GoConstantDiDefinition($valueOrFactory);
            }
            $this->override[$injectableName] = $valueOrFactory;
            return $this;
        }


        /**
         * @param array|\ReflectionParameter $parameters
         * @return array
         * @throws NoFactoryException
         */
        public function build(array $parameters) {
            $buildParams = [];
            for ($i = 0; $i < count ($parameters); $i++) {
                /* @var $curParam \ReflectionParameter */
                $curParam = $parameters[$i];

                // Check Overrides
                if (isset ($this->override[$curParam->getName()])) {
                    $buildParams[] = $this->override[$curParam->getName()]->__diGetInstance($this->mDi, ["§§name" => $curParam->getName()]);
                    continue;
                }

                if (substr ($curParam->getName(), 0, 4) === "§§") {
                    if ( ! isset ($this->override[$curParam->getName()]))
                        throw new \InvalidArgumentException("Access to override-Value: '{$curParam->getName()}' not registred");
                }

                if ($curParam->getClass() !== null && isset ($this->override[$curParam->getClass()->getName()])) {
                    $buildParams[] = $this->override[$curParam->getClass()->getName()]->__diGetInstance($this->mDi, ["§§name" => $curParam->getName()]);
                    continue;
                }

                
                if (substr ($curParam->getName(), 0, 2) === "§") {
                    if ( ! isset ($this->mDi[$curParam->getName()])) {
                        if ( ! $curParam->isOptional()) {
                            throw new NoFactoryException("No factory found for parameter {$i}: '{$curParam->getName()}'");
                        }
                        $buildParams[] = $curParam->getDefaultValue();
                        continue;
                    }
                    
                    $buildParams[] = $this->mDi[$curParam->getName()];
                    continue;
                }
                
                if ($curParam->getClass() !== null) {
                    try {
                        $instance = $this->mDi[$curParam->getClass()->getName()];
                        $buildParams[] = $instance;
                        continue;
                    } catch (NoFactoryException $e) {

                    }
                }
                
                if ($curParam->isOptional()) {
                    $buildParams[] = $curParam->getDefaultValue();
                    continue;
                }

                throw new NoFactoryException("No factory found for parameter {$i}: '{$curParam->getName()}'");
                
            }
            return $buildParams;
        }

    }