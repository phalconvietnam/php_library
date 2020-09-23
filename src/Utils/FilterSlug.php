<?php
namespace Phalconvietnam\Utils;
class FilterSlug {

    function FilterSlugFunction($str) {
        $chars = array(
            'a' =>
            array('ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'á', 'à', 'ả', 'ã', 'ạ', 'â', 'ă', 'Á', 'À', 'Ả', 'Ã', 'Ạ', 'Â', 'Ă'),
            'e' => array('ế', 'ề', 'ể', 'ễ', 'ệ', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê'),
            'i' => array('í', 'ì', 'ỉ', 'ĩ', 'ị', 'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị'),
            'o' =>
            array('ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'Ố', 'Ồ', 'Ổ', 'Ô', 'Ộ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ơ', 'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ơ'),
            'u' => array('ứ', 'ừ', 'ử', 'ữ', 'ự', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự', 'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư'),
            'y' => array('ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ'),
            'd' => array('đ', 'Đ'),    
            ''=>array('.', '/', '(', ')', '>', '<', '`', '[', ']', '?', '|', ','),
            '-' => array('"'),
            '-' => array(' ', '%20')
        );
        foreach ($chars as $key => $arr)
            foreach ($arr as $val)
                $str = str_replace($val, $key, ($str));

        return $str;
    }

    function better_strip_tags($str, $allowable_tags = '', $strip_attrs = false, $preserve_comments = false, callable $callback = null) {
        $allowable_tags = array_map('strtolower', array_filter(// lowercase
                        preg_split('/(?:>|^)\\s*(?:<|$)/', $allowable_tags, -1, PREG_SPLIT_NO_EMPTY), // get tag names
                        function( $tag ) {
                    return preg_match('/^[a-z][a-z0-9_]*$/i', $tag);
                } // filter broken
                ));
        $comments_and_stuff = preg_split('/(<!--.*?(?:-->|$))/', $str, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($comments_and_stuff as $i => $comment_or_stuff) {
            if ($i % 2) { // html comment
                if (!( $preserve_comments && preg_match('/<!--.*?-->/', $comment_or_stuff) )) {
                    $comments_and_stuff[$i] = '';
                }
            } else { // stuff between comments
                $tags_and_text = preg_split("/(<(?:[^>\"']++|\"[^\"]*+(?:\"|$)|'[^']*+(?:'|$))*(?:>|$))/", $comment_or_stuff, -1, PREG_SPLIT_DELIM_CAPTURE);
                foreach ($tags_and_text as $j => $tag_or_text) {
                    $is_broken = false;
                    $is_allowable = true;
                    $result = $tag_or_text;
                    if ($j % 2) { // tag
                        if (preg_match("%^(</?)([a-z][a-z0-9_]*)\\b(?:[^>\"'/]++|/+?|\"[^\"]*\"|'[^']*')*?(/?>)%i", $tag_or_text, $matches)) {
                            $tag = strtolower($matches[2]);
                            if (in_array($tag, $allowable_tags)) {
                                if ($strip_attrs) {
                                    $opening = $matches[1];
                                    $closing = ( $opening === '</' ) ? '>' : $closing;
                                    $result = $opening . $tag . $closing;
                                }
                            } else {
                                $is_allowable = false;
                                $result = '';
                            }
                        } else {
                            $is_broken = true;
                            $result = '';
                        }
                    } else { // text
                        $tag = false;
                    }
                    if (!$is_broken && isset($callback)) {
                        // allow result modification
                        call_user_func_array($callback, array(&$result, $tag_or_text, $tag, $is_allowable));
                    }
                    $tags_and_text[$j] = $result;
                }
                $comments_and_stuff[$i] = implode('', $tags_and_text);
            }
        }
        $str = implode('', $comments_and_stuff);
        return $str;
    }

}
