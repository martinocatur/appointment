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
 * Check whether the choosen slot is available or not before submitting event
 * Based on available_slots defined in config.json and list event on google calendar
 * @param  string  $startTime
 * @param  string  $endTime
 * @param  array $config
 * @param  array $events
 * @return boolean
 */
function isSlotAvailable($startTime, $endTime, $config = array(), $events)
{
    $date          = date_create($startTime);
    $day           = strtolower((date_format($date, "l")));
    $startHours    = substr($startTime, 11, 5);
    $endHours      = substr($endTime, 11, 5);
    $slotsOnConfig = array_column($config, $day);

    if (!empty($slotsOnConfig)) {
        if (in_array($startHours . " - " . $endHours, $slotsOnConfig) && empty($events)) {
            return true;
        }
    }

    return false;
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
