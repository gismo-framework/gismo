<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 23.07.16
     * Time: 13:36
     */


    namespace Gismo\Component\HttpFoundation\Request;


    use Gismo\Component\PhpFoundation\Accessor\IpAccessor;
    use Gismo\Component\PhpFoundation\Accessor\PathAccessor;
    use Gismo\Component\PhpFoundation\Accessor\StringAccessor;
    use Gismo\Component\PhpFoundation\Accessor\StructAccessor;
    use Gismo\Component\PhpFoundation\Accessor\UrlAccessor;

    /**
     * Class Request
     *
     *
     * @package Gismo\Component\HttpFoundation\Request
     *
     * @property StructAccessor $POST
     * @property StructAccessor $GET
     * @property UrlAccessor $URL
     * @property StructAccessor $COOKIE
     * @property IpAccessor $REMOTE_IP
     * @property UrlAccessor $ROUTE_START_URL
     * @property PathAccessor $ROUTE_PATH
     * @property StringAccessor $METHOD
     */
    class Request {

        private $POST;

        private $GET;

        private $FILES;

        private $URL;

        private $COOKIES;

        private $REMOTE_IP;

        private $HEADERS;


        private $ROUTE_START_URL;

        private $ROUTE_PATH;
        
        private $METHOD;


        public function __debugInfo() {
            return [
                "URL" => (string) $this->URL,
                "ROUTE_START_URL" => (string) $this->ROUTE_START_URL,
                "ROUTE_PATH" => (string) $this->ROUTE_PATH,
                "REMOTE_IP" => (string) $this->REMOTE_IP,
                "METHOD" => $this->METHOD,
                "GET" => $this->GET,
                "POST" => $this->POST,
                "FILES" => $this->FILES,
                "COOKIES" => $this->COOKIES,
                "HEADERS" => $this->HEADERS
            ];
        }


        public function __construct(array $data) {
            $this->POST = isset($data["POST"]) ? $data["POST"] : [];
            $this->GET = isset($data["GET"]) ? $data["GET"] : [];
            $this->FILES = isset($data["FILES"]) ? $data["FILES"] : [];
            $this->URL = isset($data["URL"]) ? $data["URL"] : null;
            $this->COOKIES = isset($data["COOKIES"]) ? $data["COOKIES"] : [];
            $this->REMOTE_IP = isset($data["REMOTE_IP"]) ? $data["REMOTE_IP"] : [];
            $this->HEADERS = isset($data["HEADERS"]) ? $data["HEADERS"] : [];

            $this->ROUTE_START_URL = isset($data["ROUTE_START_URL"]) ? $data["ROUTE_START_URL"] : null;
            $this->ROUTE_PATH = isset($data["ROUTE_PATH"]) ? $data["ROUTE_PATH"] : null;

            $this->METHOD = isset($data["METHOD"]) ? $data["METHOD"] : "GET";
        }

        public function __get($name) {
            switch ($name) {
                case "POST":
                    return new StructAccessor($this->POST);
                case "GET":
                    return new StructAccessor($this->GET);
                case "FILES":
                    $arr = $this->filterFiles($this->FILES);
                    return new StructAccessor($arr);
                case "URL":
                    return new UrlAccessor($this->URL);
                case "COOKIES":
                    return new StructAccessor($this->COOKIES);
                case "REMOTE_IP":
                    return new IpAccessor($this->REMOTE_IP);
                case "HEADERS":
                    return new StructAccessor($this->HEADERS, [], NULL, FALSE);

                case "ROUTE_START_URL":
                    return new UrlAccessor($this->ROUTE_START_URL);

                case "ROUTE_PATH":
                    return new PathAccessor($this->ROUTE_PATH);

                case "METHOD":
                    return new StringAccessor($this->METHOD);
                default:
                    throw new \InvalidArgumentException("Invalid property: Request::$'$name'");
            }
        }

        public function __set($name, $value) {
            switch ($name) {
                case "POST":
                    $this->POST = $value;
                    break;
                case "GET":
                    $this->GET = $value;
                    break;
                case "FILES":
                    $this->FILES = $value;
                    break;
                case "URL":
                    $this->URL = $value;
                    break;
                case "COOKIES":
                    $this->COOKIES = $value;
                    break;
                case "REMOTE_IP":
                    $this->REMOTE_IP = $value;
                    break;
                case "HEADERS":
                    $this->HEADERS = $value;
                    break;
                case "INFO_PATH":
                    $this->PATH_INFO = $value;
                    break;
                default:
                    throw new \InvalidArgumentException("Invalid setter: Request::$'$name'");
            }
        }

        public function filterFiles($arr) {
            if ( ! isset ( $arr["name"] ) || ! isset ( $arr["tmp_name"] )) {
                throw new \Exception("parameter is no array from \$_FILES[]");
            }
            $ret = [];
            foreach ($arr as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    if ($key1 === "error") {
                        switch ($value2) {
                            case 0:
                                break; // No Error
                            case 1:
                                throw new \Exception("Upload exceeds upload limit.");
                            case 2:
                                throw new \Exception("Upload exceeds upload form-limit.");
                            case 3:
                                throw new \Exception("Upload was only partially uploaded");
                            case 4:
                                throw new \Exception("No File was uploaded");
                            case 5:
                                throw new \Exception("FileUpload: Missing tempoary folder.");
                            case 6:
                                throw new \Exception("FileUpload: Failed to write to disc.");
                            case 7:
                                throw new \Exception("FileUpload: A PHP Extension sotpped the file upload (Code: UPLOAD_ERR_EXTENSION)");
                            default:
                                throw new \Exception("FileUpload: Unrecognized error-code: {$value2}");
                        }
                    }
                    $ret[$key2][$key1] = $value2;
                }
            }
            return $ret;
        }

    }