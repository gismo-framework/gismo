<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 23.07.16
     * Time: 13:42
     */

    namespace Gismo\Component\HttpFoundation\Request;

    use Doctrine\Instantiator\Exception\InvalidArgumentException;
    use Gismo\Component\Config\AppConfig;
    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\PhpFoundation\Accessor\IpAccessor;

    class RequestFactory {


        public static function BuildRequestByUrl(string $url) : Request {
            $url = goUrl($url);
            $data = [
                    "URL" => (string)$url,
                    "GET" => $url->query->getValue(),
                    "REQUEST_URI" => $url->path,
                    "SCRIPT_NAME" => "",
                    "METHOD" => "GET",

            ];
            return new Request($data);
        }

        public static function BuildFromEnv(AppConfig $config, $server=null) : Request {
            if ($server === null)
                $server = $_SERVER;

            $data = [];

            $requestUrl = $server["REQUEST_SCHEME"] . "://" . $server["HTTP_HOST"];
            if ($server["SERVER_PORT"] != "80") {
                $requestUrl .= ":" . $server["SERVER_PORT"];
            }
            $requestHostAndScheme = $requestUrl;

            $requestUrl .= $server["REQUEST_URI"];

            $data["URL"] = $requestUrl;
            $data["GET"] = $_GET;
            $data["POST"] = $_POST;
            $data["FILES"] = $_FILES;
            $data["COOKIES"] = $_COOKIE;



            $headers = [];
            foreach ($server as $key => $val) {
                if (strpos ($key, "HTTP_") !== 0)
                    continue;
                $headers[substr($key, 5)] = $val;
            }
            $data["HEADERS"] = $headers;

            $data["REMOTE_IP"] = $server["REMOTE_ADDR"];
            if ((new IpAccessor($server["REMOTE_ADDR"]))->isInSubnet($config->secureProxyNet)) {
                if ( ! isset ($data["HEADERS"][$config->secureForwardIpHeader]))
                    throw new \InvalidArgumentException("Forwarded IpHeader (Config: 'secureForwardIpHeader') is missing. Edit your nginx config.");
                $data["REMOTE_IP"] = $data["HEADERS"][$config->secureForwardIpHeader];
            }

            $scriptPath = dirname($server["SCRIPT_NAME"]);
            if ($scriptPath == "/")
                $scriptPath = "";

            $data["ROUTE_START_PATH"] = $scriptPath;
            $data["ROUTE_START_URL"] = $requestHostAndScheme . $scriptPath;

            if ( $scriptPath !== "" && ! strpos($server["REQUEST_URI"], $scriptPath) === 0)
                throw new \InvalidArgumentException("Invalid RequestUri: '{$server['REQUEST_URI']}'. Must start with Script Path: '$scriptPath'");



            $data["ROUTE_PATH"] = substr ($server["REQUEST_URI"], strlen($scriptPath));
            $data["ROUTE_PATH"] = explode("?", $data["ROUTE_PATH"])[0];

            return new Request($data);

        }

    }