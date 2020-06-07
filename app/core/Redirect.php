<?php

namespace App\Core;

class Redirect
{

    public static function to($url)
    {
        $link = 'http://' . $_SERVER['HTTP_HOST'] .
            str_replace(
                $_SERVER['DOCUMENT_ROOT'],
                '',
                str_replace('\\', '/', INC_ROOT) . '/public'
            );

        header('Location:' . $link . '/' . $url);
        die();
    }

    public static function back()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        }
    }
}
