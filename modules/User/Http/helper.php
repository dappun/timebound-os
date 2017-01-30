<?php
if (!function_exists('userTimezone')) {

function userTimezone()
{
    if (\Auth::check()) {
        $ustz = \Auth::user()->getTimezone();
    } else {
        $ustz = \Config::get('core.timezone.display');
    }

    return $ustz;
}

}