<?php

namespace Appointment;

use function Appointment\filterDate;
use function Appointment\filterKeyOnArr;
use function Appointment\getDayFromDate;
use function Appointment\getIntervalBetweenTime;
use function Appointment\getTimeFromDate;

/**
 *
 */
class SlotHandler
{
    const DEFAULT_DURATION = 60;
    /**
     * getAvailableSlots
     * @param  array    $slotsOnConfig
     * @param  array     $events
     * @param  \DateTime $start
     * @param  \DateTime $end
     * @param  integer   $duration
     * @return array
     */
    public function getAvailableSlots(
        $slotsOnConfig = array(),
        $events = array(),
        $start,
        $end,
        $duration = self::DEFAULT_DURATION
    ) {
        $availableSlots = [];

        $timeSlotOnConfig = $this->getTimeSlot(
            filterKeyOnArr($slotsOnConfig, 0)
        );
        $startTimeOnConfig = $timeSlotOnConfig['start'];
        $endTimeOnConfig   = $timeSlotOnConfig['end'];
        $startTime         = getTimeFromDate($start);
        $endTime           = getTimeFromDate($end);
        if (!empty($timeSlotOnConfig)) {
            if (strtotime($startTime) >= strtotime($startTimeOnConfig) && strtotime($endTime) <= strtotime($endTimeOnConfig)) {
                $bookedSlots = $this->getBookedSlotsFromEvents($events);
                if (count($bookedSlots) == 0) {
                    array_push($availableSlots, $timeSlotOnConfig);
                } else {
                    $availableSlots = $this->getAvailableSlotsBetweenBookedSlots($bookedSlots, $startTimeOnConfig, $endTimeOnConfig, $duration);
                }
            }
        }

        return $availableSlots;
    }
    /**
     * Get available slots
     * Based on choosen date,
     * available_slots defined in config.json
     * and list event on google calendar
     * @param  array $bookedSlots
     * @param  int $duration
     * @return array
     */
    public function getAvailableSlotsBetweenBookedSlots(
        $bookedSlots,
        $startTimeOnConfig,
        $endTimeOnConfig,
        $duration
    ) {
        $result = [];

        $bookedSlotsLength = count($bookedSlots);
        for ($i = 0; $i < $bookedSlotsLength; $i++) {
            $startTimeBooked = $bookedSlots[$i]['start'];
            $endTimeBooked   = $bookedSlots[$i]['end'];
            if ($i == 0 && $this->filterDuration(getIntervalBetweenTime($startTimeOnConfig, $startTimeBooked), $duration)) {
                array_push($result, [
                    'start' => $startTimeOnConfig,
                    'end'   => $startTimeBooked,
                ]);
            }
            if ($i == ($bookedSlotsLength - 1) && $this->filterDuration(getIntervalBetweenTime($endTimeBooked, $endTimeOnConfig), $duration)) {
                array_push($result, [
                    'start' => $endTimeBooked,
                    'end'   => $endTimeOnConfig,
                ]);
            }
            if ($i < ($bookedSlotsLength - 1)) {
                $startTimeBooked = $bookedSlots[$i + 1]['start'];
                if ($this->filterDuration(getIntervalBetweenTime($endTimeBooked, $startTimeBooked), $duration)) {
                    array_push($result, [
                        'start' => $endTimeBooked,
                        'end'   => $startTimeBooked,
                    ]);
                }
            }
        }

        return $result;
    }
    /**
     * mapping start & end dates
     * from events object
     * @param  array $events
     * @return array
     */
    public function getBookedSlotsFromEvents($events)
    {
        $result = array_map(function ($event) {
            $startTime = getTimeFromDate(
                filterDate(
                    date_create($event->getStart()->dateTime)
                )
            );
            $endTime = getTimeFromDate(
                filterDate(
                    date_create($event->getEnd()->dateTime)
                )
            );
            return [
                'start' => $startTime,
                'end'   => $endTime,
            ];
        }, $events);

        return $result;
    }
    /**
     * getSlotAvailableOnConfig
     * @param  string $date
     * @param  array  $config
     * @return array
     */
    public function getSlotAvailableOnConfig($date, $config)
    {
        $day    = getDayFromDate($date);
        $slots  = $config;
        $result = array_column($slots, $day);

        return $result;
    }
    /**
     * getTimeSlot
     * @param  string $slot ex. 10:00 - 11:00
     * @return array
     */
    private function getTimeSlot($slot)
    {
        $times  = explode('-', $slot);
        $result = array_map(function ($time) {
            return trim($time);
        }, $times);
        return array_combine(["start", "end"], $result);
    }
    /**
     * Filter duration
     * @param  int $durationAvailable
     * @param  int $minimum
     * @return bool
     */
    private function filterDuration($durationAvailable, $minimum)
    {
        if ($durationAvailable >= $minimum) {
            return true;
        }
        return false;
    }
    /**
     * Filter slots
     * @param  array $slots
     * @return array
     */
    private function filterSlots($slots)
    {
        $filtered = array();
        foreach ($slots as $slot) {
            if ($slot->duration > -1) {
                array_push($filtered, $slot);
            }
        }
        return $filtered;
    }
    /**
     * Create slot object
     * @param  int $duration
     * @param  string $t1
     * @param  string $t2
     * @return \StdClass
     */
    private function createSlotObject($duration, $t1, $t2)
    {
        $object           = new \StdClass;
        $object->t1       = $t1;
        $object->t2       = $t2;
        $object->duration = $duration;
        return $object;
    }
}
