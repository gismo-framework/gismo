<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 7/25/17
 * Time: 10:03 PM
 */

namespace Gismo\Component\PhpFoundation\Helper;


class PathHelper
{

    /**
     * Clean up a Path and strip the leading slash
     *
     * @param $input array|string
     * @return bool|mixed|string
     */
    public static function Relative ($input) {
        if (is_array($input))
            $input = implode("/", $input);
        $count = 1;
        while ($count > 0) {
            $input = str_replace("//", "/", $input, $count);
        }
        if (substr ($input, 0, 1) == "/")
            $input = substr($input, 1);
        return $input;
    }

    /**
     *
     *
     * @param $input
     * @return string
     */
    public static function Absolute ($input) {
        return "/" . self::Relative($input);
    }
}