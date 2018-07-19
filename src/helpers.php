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
 * [stringToDate description]
 * @param  string $strDate
 * @return mixed
 */
function stringToDate($strDate)
{
    $date = new \DateTime($strDate);
    if (!$date) {
        throw new Exception("Can't create date, invalid format.", 1);
    }
    return $date;
}
/**
 * get interval between two times
 * @param  string $startTime example. 10:00
 * @param  string $endTime   example. 11:00
 * @return int
 */
function getIntervalBetweenTime($startTime, $endTime)
{
    $start = strtotime('1/1/1990 ' . $startTime);
    $end = strtotime('1/1/1990 ' . $endTime);

    return ($end - $start) / 60;
}
/**
 * getDayFromRfcDate
 * @param  string $date format RFC
 * @return string lowercase
 */
function getDayFromDate(\DateTime $date)
{
    $day = strtolower((date_format($date, "l")));
    return $day;
}
/**
 * getTimeFromDate
 * @param  \DateTime $date
 * @return string
 */
function getTimeFromDate(\DateTime $date)
{
    $time = date_format($date, 'H:i');
    return $time;
}
/**
 * Get duration of time
 * @param  string $dateTime1
 * @param  string $dateTime2
 * @return int
 */
function getDuration($dateTime1, $dateTime2)
{
    $dateTime1 = setDate($dateTime1)->format('Y-m-d\TH:i:sP');
    $dateTime2 = setDate($dateTime2)->format('Y-m-d\TH:i:sP');
    $duration = (strtotime($dateTime1) - strtotime($dateTime2)) / 60;

    return $duration;
}
/**
 * Create Date
 * @param  string $h
 * @param  string $m
 * @return string
 */
function createDateRFC($h, $m)
{
    $today = date("Y-m-d");
    $date = new \DateTime($today);
    $date->setTime((int) $h, (int) $m);
    return $date->format('Y-m-d\TH:i:sP');
}
/**
 * Set Date
 * @param \DateTime
 */
function setDate($date)
{
    $newDate = new \DateTime($date);

    $newDate->setDate(1980, 1, 1);

    return $newDate;
}
