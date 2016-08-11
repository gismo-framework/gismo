<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 06.07.16
     * Time: 18:32
     */    
    
    namespace Gismo\Component\PhpFoundation\Accessor;
    use Doctrine\Instantiator\Exception\InvalidArgumentException;
    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;

    /**
     * Class UrlAccessor
     *
     *
     * Folgende liste wird von PHPStorm interpretiert:
     *
     * @property string     $url
     * @property StringAccessor  $scheme
     * @property StringAccessor $host
     * @property StringAccessor $hosts
     * @property NumberAccessor     $port
     * @property StringAccessor  $user
     * @property StringAccessor  $pass
     * @property PathAccessor $path
     * @property StructAccessor $query
     * @property StringAccessor $queryString
     * @property StringAccessor $fragment
     * @package pf\accessor
     */
    class UrlAccessor extends AbstractAccessor {

    
        private $url;
    
    
        private $scheme;
    
    
        private $host;
    
        private $hosts;
    
        private $port;
    
        private $user;
    
        private $pass;
    
    
        /**
         * Accessed by __ge() and __set()
         *
         * @var string
         */
        private $path;
    
    
        /**
         * Accessed by __get() and __set()
         *
         * @var array
         */
        private $query;

        private $queryString;
    
    
        private $fragment;
    
    
    
       
        public function __construct(string $rawValue, $isImmutable=false) {
            $this->__filterIn($rawValue);
            parent::__construct(null, $isImmutable);
        }


        public function __toString() {
            return $this->__filterOut();
        }


        public function __get($name) {
            switch ($name) {
                case "url":
                    return $this->__filterOut();

                case "scheme":
                    return new StringAccessor($this->scheme);

                case "port":
                    return new NumberAccessor($this->port);

                case "user":
                    return new StringAccessor($this->user);

                case "pass":
                    return new StringAccessor($this->pass);

                case "host":
                    return new StringAccessor($this->host);

                case "path":
                    return new PathAccessor($this->path);
    
                case "query":
                    return new StructAccessor($this->query);

                case "queryString":
                    return new StringAccessor($this->queryString);

                case "fragment":
                    return new StringAccessor($this->fragment);

                default:
                    throw new \InvalidArgumentException("Invalid property: UrlAccessor::$'$name'");
            }
        }
    
        public function __set($name, $value) {

            switch ($name) {
                case "path":
                    $this->path = new PathAccessor($value);
                    break;
    
                case "url":
                    $this->__filterIn($value);
                    break;
    
                default:
                    throw new \InvalidArgumentException("Invalid setter: TapUrl::$'$name'");
            }
        }
    
    
        protected function __filterIn(string $val) {

            $parsed = parse_url($val);
            if ($parsed === false)
                throw new ExpectationFailedException(["Cannot parse_url() on ?", $val], null, $val);
    
            parse_str(@$parsed["query"], $queryArr);
            $this->scheme = @$parsed["scheme"];
            $this->host = @$parsed["host"];
            $this->hosts = explode(",", @$parsed["host"]);
            $this->port = @$parsed["port"];
            $this->user = @$parsed["user"];
            $this->pass = @$parsed["pass"];
            $this->fragment = @$parsed["fragment"];
            $this->query = $queryArr;
            $this->queryString = @$parsed["query"];
            $this->path = new PathAccessor(@$parsed["path"]);
        }
    
    
        protected function __filterOut() {
            $ret = $this->scheme . "://";
            if ($this->user !== null || $this->pass !== null) {
                $ret .= urlencode($this->user) . ":" . urlencode($this->pass) . "@";
            }
            $ret .= $this->host;
            if ($this->port !== null)
                $ret .= ":{$this->port}";
            $ret .= (string)$this->path->toAbsolutePath();
            if ( ! empty($this->query))
                $ret .= "?" . http_build_query($this->query);
    
            if ($this->fragment !== null)
                $ret .= "#" . urlencode($this->fragment);
            return $ret;
        }



        public function isMultiHostUrl() {
    
        }
    
        
    
    }