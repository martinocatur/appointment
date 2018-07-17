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
    $date = date_create($startTime);
    $day = strtolower((date_format($date, "l")));
    $startHours = substr($startTime, 11, 5);
    $endHours = substr($endTime, 11, 5);
    $slotsOnConfig = array_column($config, $day);

    if (!empty($slotsOnConfig)) {
        if (in_array($startHours . " - " . $endHours, $slotsOnConfig) && empty($events)) {
            return true;
        }
    }

    return false;
}
