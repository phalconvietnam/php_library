<?php
namespace Phalconvietnam\Utils;
class FilterImage
{
    function filterImage($html)
    {
        if (stripos($html, '<img') !== false) {
            //$imgsrcRegex = '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im';
            $imgsrcRegex='/<img .*src=["|\']([^"|\']+)/i';
            preg_match($imgsrcRegex, $html, $matches);
            unset($imgsrcRegex);
            unset($html);
            if (is_array($matches) && !empty($matches)) {
                return $matches[1];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}