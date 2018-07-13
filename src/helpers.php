<?php

namespace Appointment;

/**
 * Filter string
 * @param  mixed $str
 * @return string
 * @throws Exception
 */
function filterString($str)
{
    if (is_string($str)) {
        return $str;
    }
    throw new \Exception("Not a string", 1);
}
