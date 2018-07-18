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
function getAvailableSlots($startTime, $endTime, $config = array(), $events)
{
    $date = date_create($startTime);
    $day = strtolower((date_format($date, "l")));
    $startHour = getHourFromRFCDate($startTime);
    $endHour = getHourFromRFCDate($endTime);
    $slotsOnConfig = array_column($config, $day);
    $availableSlots = [];

    //temporary hardcoded
    $eventTypeDuration = 60;

    if (!empty($slotsOnConfig)) {
        $startOnConfig = substr($slotsOnConfig[0], 0, 5);
        $endOnConfig = substr($slotsOnConfig[0], 8, 5);

        if ($startHour >= $startOnConfig && $endHour <= $endOnConfig) {
            $bookedSlots = tmpEventsToArray($events);
            $bookedSlotsLength = count($bookedSlots);
            
            if ($bookedSlotsLength == 0) {
                array_push($availableSlots, $slotsOnConfig[0]);
            }

            for ($i = 0; $i < $bookedSlotsLength; $i++) {
                if ($i == 0 && getInterval($startOnConfig, getHourFromRFCDate($bookedSlots[$i]['start'])) >= $eventTypeDuration) {
                    array_push($availableSlots, [
                        'start'=>$startOnConfig,
                        'end'=>$bookedSlots[$i]['start']
                    ]);
                }

                if ($i == ($bookedSlotsLength - 1) && getInterval(getHourFromRFCDate($bookedSlots[$i]['end']), $endOnConfig) >= $eventTypeDuration) {
                    array_push($availableSlots, [
                        'start'=>$bookedSlots[$i]['end'],
                        'end'=>$endOnConfig
                    ]);
                }

                if ($i < ($bookedSlotsLength - 1)) {
                    if (getInterval(getHourFromRFCDate($bookedSlots[$i]['end']), getHourFromRFCDate($bookedSlots[$i + 1]['start'])) >= $eventTypeDuration) {
                        array_push($availableSlots, [
                            'start'=>$bookedSlots[$i]['end'],
                            'end'=>$bookedSlots[$i + 1]['start']
                        ]);
                    }
                }
            }
        }
    }
    return $availableSlots;
}
/**
 * get interval between two times
 * @param  string $startTime example. 10:00
 * @param  string $endTime   example. 11:00
 * @return int
 */
function getInterval($startTime, $endTime)
{
    $start = strtotime('1/1/1990 ' . $startTime);
    $end = strtotime('1/1/1990 ' . $endTime);

    return ($end - $start) / 60;
}
/**
 * get hour from rfc date format
 * @param  string $date ex. 2018-07-09T14:30:00+07:00
 * @return string
 */
function getHourFromRFCDate($date)
{
    return substr($date, 11, 5);
}
/**
 * mapping start & end dates from events object
 * @param  array $events
 * @return array
 */
function tmpEventsToArray($events)
{
    $result = array_map(function ($event) {
        return [
            'start'=>$event->getStart()->dateTime,
            'end'=>$event->getEnd()->dateTime
        ];
    }, $events);

    return $result;
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
