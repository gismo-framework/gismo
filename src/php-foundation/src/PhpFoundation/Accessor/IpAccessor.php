<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 25.07.16
     * Time: 16:39
     */

    namespace Gismo\Component\PhpFoundation\Accessor;



    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;

    class IpAccessor extends AbstractAccessor {


        public function isIpv4() {
            return filter_var($this->reference, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
        }

        public function isIpv6() {
            return filter_var($this->reference, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
        }

        public function isPrivateSpace () {
            return ! (filter_var($this->reference, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) !== false);
        }



        public function expectIsInSubnet($cidr) : self {
            if ( ! $this->isInSubnet($cidr))
                throw new ExpectationFailedException(["Ip ? is excpected to be in cidr ?", $this->reference, $cidr], null, $this->reference);
            return $this;
        }


        /**
         * @param $cidr
         * @return bool
         */
        public function isInSubnet ($cidr) {
            $ip = $this->reference;

            if ($this->isIpv6()) {
                if (!((extension_loaded('sockets') && defined('AF_INET6')) || @inet_pton('::1'))) {
                    throw new \RuntimeException('Unable to check Ipv6. Check that PHP was not compiled with option "disable-ipv6".');
                }
                if (false !== strpos($ip, '/')) {
                    list($address, $netmask) = explode('/', $ip, 2);
                    if ($netmask < 1 || $netmask > 128) {
                        return false;
                    }
                } else {
                    $address = $ip;
                    $netmask = 128;
                }
                $bytesAddr = unpack('n*', @inet_pton($address));
                $bytesTest = unpack('n*', @inet_pton($cidr));
                if (!$bytesAddr || !$bytesTest) {
                    return false;
                }
                for ($i = 1, $ceil = ceil($netmask / 16); $i <= $ceil; ++$i) {
                    $left = $netmask - 16 * ($i - 1);
                    $left = ($left <= 16) ? $left : 16;
                    $mask = ~(0xffff >> $left) & 0xffff;
                    if (($bytesAddr[$i] & $mask) != ($bytesTest[$i] & $mask)) {
                        return false;
                    }
                }
                return true;
            } else {
                if (false !== strpos($cidr, '/')) {
                    list($address, $netmask) = explode('/', $cidr, 2);
                    if ($netmask === '0') {
                        // Ensure IP is valid - using ip2long below implicitly validates, but we need to do it manually heref
                        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
                    }
                    if ($netmask < 0 || $netmask > 32) {
                        return false;
                    }
                } else {
                    $address = $cidr;
                    $netmask = 32;
                }
                return 0 === substr_compare(sprintf('%032b', ip2long($ip)), sprintf('%032b', ip2long($address)), 0, $netmask);
            }

        }


    }