<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 23.07.16
     * Time: 13:42
     */

    namespace Gismo\Component\HttpFoundation\Request;

    use Doctrine\Instantiator\Exception\InvalidArgumentException;
    use Gismo\Component\HttpFoundation\Request\Request;

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

        public static function BuildFromEnv($server=null) : Request {
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

            $data["REMOTE_IP"] = $server["REMOTE_ADDR"];


            $headers = [];
            foreach ($server as $key => $val) {
                if (strpos ($key, "HTTP_") !== 0)
                    continue;
                $headers[substr($key, 5)] = $val;
            }
            $data["HEADERS"] = $headers;


            $scriptPath = dirname($server["SCRIPT_NAME"]);
            $data["ROUTE_START_URL"] = $requestHostAndScheme . $scriptPath;

            if ( ! strpos($server["REQUEST_URI"], $scriptPath) === 0)
                throw new \InvalidArgumentException("Invalid RequestUri: '{$server['REQUEST_URI']}'. Must start with Script Path: '$scriptPath'");



            $data["ROUTE_PATH"] = substr ($server["REQUEST_URI"], strlen($scriptPath));
            $data["ROUTE_PATH"] = explode("?", $data["ROUTE_PATH"])[0];

            return new Request($data);

        }

    }