<?php

    function filter($num){
        $result = null;
        $pattern = preg_quote('#$%^&*()+=_-[]\';,/{}|\":<>?~', '#');
        $result = preg_replace("#[{$pattern}]#", "",$num);
        return $result;
    }

    function FontNumaber($num){
        if ($num != null){
            $result = str_replace(array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'), $num);
        }else{
            $result = "----";
        }
        return $result;
    }