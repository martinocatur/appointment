<?php

namespace Appointment;

use function Appointment\createDateRFC;
use function Appointment\getDuration;
use function Appointment\stringToDate;
use function Appointment\getDayFromDate;
use function Appointment\getTimeFromDate;
use function Appointment\getIntervalBetweenTime;

/**
 *
 */
class SlotHandler
{
    /**
     * Get available slots
     * @param  int $duration
     * @param  string $daySlot
     * @param  \Google_Service_Calendar_Event $events
     * @return array
     */
    public function getAvailableSlotzs($duration, $daySlot, $events)
    {
        $schedules = array();

        $daySlot = explode("-", $daySlot);

        $slots = [];

        foreach ($daySlot as $slot) {
            $slots[] = trim($slot);
        }

        $startTime = explode(":", $slots[0]);

        $endTime = explode(":", $slots[1]);

        $d1 = createDateRFC($startTime[0], $startTime[1]);

        $d2 = createDateRFC($endTime[0], $endTime[1]);

        if (empty($events)) {
            return [$this->createSlotObject(
                getDuration($d2, $d1),
                $d1,
                $d2
            )];
        }

        $schedules [] = $this->createSlotObject(
            $this->filterDuration(

                getDuration(

                    $events[0]->getStart()->getDateTime(),
                    $d1
                ),
                $duration
            ),
            $d1,
            $events[0]->getStart()->getDateTime()


        );

        $numOfEvents = count($events);

        for ($i = 1; $i < $numOfEvents; $i++) {
            $eventBefore = $events[$i - 1];
            $eventAfter  = $events[$i];
            $duration    = $this->filterDuration(
                getDuration(
                    $eventAfter->getStart()->getDateTime(),
                    $eventBefore->getEnd()->getDateTime()
                ),
                $duration
            );

            $schedules[] = $this->createSlotObject(
                $duration,
                $eventBefore->getEnd()->getDateTime(),
                $eventAfter->getStart()->getDateTime()
            );
        }

        $schedules [] = $this->createSlotObject(
            $this->filterDuration(
            getDuration(

                $d2,
                $events[count($events) - 1]->getEnd()->getDateTime()
            ),
            $duration
        ),
        $events[count($events) - 1]->getEnd()->getDateTime(),
        $d2
        );

        return $this->filterSlots($schedules);
    }
    /**
     * Filter duration
     * @param  int $durationAvailable
     * @param  int $minimum
     * @return int
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
    /**
     * getSlotAvailableOnConfig
     * @param  \DateTime $date
     * @param  array    $config
     * @return array
     */
    public function getSlotAvailableOnConfig(\DateTime $date, $config)
    {
        $day = getDayFromDate($date);
        $slots = $config;
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
        $times = explode('-', $slot);
        $result = array_map(function ($time) {
            return trim($time);
        }, $times);
        return array_combine(["start","end"], $result);
    }
    /**
     * getAvailableSlots
     * @param  array     $slotsOnConfig
     * @param  array     $events
     * @param  \DateTime $start
     * @param  \DateTime $end
     * @param  integer   $duration
     * @return array
     */
    public function getAvailableSlots($slotsOnConfig = array(), $events = array(), \DateTime $start, \DateTime $end, $duration = 60)
    {
        $availableSlots = [];

        $timeSlotOnConfig = $this->getTimeSlot($slotsOnConfig);
        $startTimeOnConfig = $timeSlotOnConfig['start'];
        $endTimeOnConfig = $timeSlotOnConfig['end'];

        $day = getDayFromDate($start);
        $startTime = getTimeFromDate($start);
        $endTime = getTimeFromDate($end);
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
     * getAvailableSlotsBetweenBookedSlots
     * Based on choosen date, available_slots defined in config.json and list event on google calendar
     * @param  array $bookedSlots
     * @param  int $duration
     * @return array
     */
    public function getAvailableSlotsBetweenBookedSlots($bookedSlots, $startTimeOnConfig, $endTimeOnConfig, $duration)
    {
        $result = [];

        $bookedSlotsLength = count($bookedSlots);
        for ($i=0; $i < $bookedSlotsLength; $i++) {
            $startTimeBooked = $bookedSlots[$i]['start'];
            $endTimeBooked = $bookedSlots[$i]['end'];
            if ($i == 0 && $this->filterDuration(getIntervalBetweenTime($startTimeOnConfig, $startTimeBooked), $duration)) {
                array_push($result, [
                    'start'=>$startTimeOnConfig,
                    'end'=>$startTimeBooked
                ]);
            }
            if ($i == ($bookedSlotsLength - 1) && $this->filterDuration(getIntervalBetweenTime($endTimeBooked, $endTimeOnConfig), $duration)) {
                array_push($result, [
                    'start'=>$endTimeBooked,
                    'end'=>$endTimeOnConfig
                ]);
            }
            if ($i < ($bookedSlotsLength - 1)) {
                $startTimeBooked = $bookedSlots[$i + 1]['start'];
                if ($this->filterDuration(getIntervalBetweenTime($endTimeBooked, $startTimeBooked), $duration)) {
                    array_push($result, [
                        'start'=>$endTimeBooked,
                        'end'=>$startTimeBooked
                    ]);
                }
            }
        }

        return $result;
    }
    /**
    * mapping start & end dates from events object
    * @param  array $events
    * @return array
    */
    public function getBookedSlotsFromEvents($events)
    {
        $result = array_map(function ($event) {
            $start = date_create($event->getStart()->dateTime);
            $end = date_create($event->getEnd()->dateTime);
            $startTime = getTimeFromDate($start);
            $endTime = getTimeFromDate($end);

            return [
                'start'=>$startTime,
                'end'=>$endTime
            ];
        }, $events);

        return $result;
    }
}
