<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 10.06.16
 * Time: 22:39
 */


    namespace Gismo\Component\Config;


    
    class AppConfig {


        /**
         *
         *
         * @var string
         */
        public $ENVIRONMENT;





        /**
         * Für testing
         *
         * @var string
         */
        public $httpRootUrl = "";


        /**
         * Durch Komma getrennte liste von CIDR Adressbereichen
         *
         * 1.2.3.4/24,4.4.4.4/8
         *
         * Anfragen aus diesem Netz werden als Interne Proxies (Accelerator) wahrgenommen
         * und die original-IP aus dem u.a. Header gezogen.
         *
         * @var string
         */
        public $secureProxyNet = "127.0.0.1/32";

        /**
         * Den Header, in dem die Ursprüngliche IP-Adresse erwartet wird,
         * wenn die Original-Zugriffs-Ip in $secureProxyNet hängt.
         *
         * @var string
         */
        public $secureForwardIpHeader = "X-GISMO-FORWARDED-FOR";


        /**
         * Durch Komma getrennte Liste mit CIDR Adressbereichen, deren
         * Anfragen als Internal markiert werden (d.h. i.d.R. die
         * Servernetze zwischen den Servern.
         *
         * Diese IPs erhalten direkten Zugriff auf die apis
         *
         * @var array
         */
        public $internalNetworks = "127.0.0.1/32";

        /**
         * @var string
         */
        public $secureNetworks = "127.0.0.1/32";

    }