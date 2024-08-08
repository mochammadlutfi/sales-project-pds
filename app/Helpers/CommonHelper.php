<?php

// app/Helpers/Helper.php

if (!function_exists('errorBag')) {
    /**
     * Example custom helper function.
     *
     * @param  string  $param
     * @return string
     */
    function errorBag($data)
    {
        return (object) collect($data)->map(function ($errors) {
            return $errors[0];
        })->toArray();
    }
}