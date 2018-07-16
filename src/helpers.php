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
 * [isSlotAvailable description]
 * @param  string  $startTime
 * @param  string  $endTime
 * @param  array $dateConfigs
 * @param  array $eventsOnGCal
 * @return boolean
 */
function isSlotAvailable($startTime, $endTime, $dateConfigs, $eventsOnGCal)
{
    $date = date_create($startTime);
    $day = strtolower((date_format($date, "l")));
    $startHours = substr($startTime, 11, 5);
    $endHours = substr($endTime, 11, 5);

    $slotsOnConfig = array_column($dateConfigs['available_slots'], $day);
    
    if (!empty($slotsOnConfig)) {
        if (in_array($startHours." - ".$endHours, $slotsOnConfig[0]) && $eventsOnGCal->count()==0) {
            return true;
        }
    }

    return false;
}
