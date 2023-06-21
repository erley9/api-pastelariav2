<?php
if(!function_exists("clearPhoneNumber"))
{
    function clearString($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }
}
