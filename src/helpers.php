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
/**
 * Filter file path
 * @param  string $path
 * @return string
 * @throws \Exception
 */
function filterFilePath($path)
{
    if (!file_get_contents($path)) {
        throw new \Exception("File not found", 1);
    }
    return $path;
}
/**
 * Filter an array based on allowed keys
 * @param  array  $arr
 * @param  array  $allowedKey
 * @return array
 */
function filterArrayKey($arr = array(), $allowedKey = array())
{
    if (!empty($allowedKey)) {
        $arr = array_filter(
            $arr,
            function ($key) use ($allowedKey) {
                return in_array($key, $allowedKey);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    return $arr;
}
/**
 * get interval between two times
 * @param  string $startTime example. 10:00
 * @param  string $endTime   example. 11:00
 * @return int
 */
function getIntervalBetweenTime($startTime, $endTime)
{
    $start = strtotime('1/1/1980 ' . $startTime);
    $end = strtotime('1/1/1980 ' . $endTime);

    return ($end - $start) / 60;
}
/**
 * getDayFromRfcDate
 * @param  string $date format RFC
 * @return string lowercase
 */
function getDayFromDate($date)
{
    $day = strtolower((date_format($date, "l")));
    return $day;
}
/**
 * getTimeFromDate
 * @param  bool|\DateTime $date
 * @return string
 */
function getTimeFromDate($date)
{
    $time = date_format($date, 'H:i');
    return $time;
}
/**
 * Filter date instance
 * @param  \DateTime|bool $date
 * @return \DateTime|bool
 * @throws \Exception
 */
function filterDate($date)
{
    if (!$date) {
        throw new \Exception("Invalid date", 1);
    }
    return $date;
}
/**
 * Filter key existence on array
 * @param  array $arr
 * @param  mixed $key
 * @return mixed
 * @throws \Exception
 */
function filterKeyOnArr($arr, $key)
{
    if (!isset($arr[$key])) {
        throw new \Exception("Key undefined", 1);
    }
    return $arr[$key];
}
