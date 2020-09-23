<?php
namespace Phalconvietnam\Utils;
class FilterText {
    function FilterText($str, $limit, $end_char = '&#8230;') {
        $str = strip_tags($str);
        if (trim($str) == '') {
            return $str;
        }
        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);

        if (strlen($str) == strlen($matches[0])) {
            $end_char = '';
        }

        return rtrim($matches[0]) . $end_char;
    }
    function ulimit($str, $limit = 100, $end_char = '&#8230;') {

        if (trim($str) == '') {
            return $str;
        }
        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);

        if (strlen($str) == strlen($matches[0])) {
            $end_char = '';
        }
        return rtrim($matches[0]) . $end_char;
    }
    function climit($str, $n = 50, $end_char = '&#8230;') {

        if (strlen($str) < $n) {
            return $str;
        }
        $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

        if (strlen($str) <= $n) {
            return $str;
        }

        $out = "";
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';
            if (strlen($out) >= $n) {
                return trim($out) . $end_char;
            }
        }
    }

}