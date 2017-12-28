<?php

if (! function_exists('p')) {
    /**
     * Function for debug issues
     *
     * @param $value
     *
     * @return string
     */
    function p($value) {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    }
}