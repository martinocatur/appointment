<?php

namespace Appointment;

use function Appointment\createDateRFC;
use function Appointment\getDuration;

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
    public function getAvailableSlots($duration, $daySlot, $events)
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
            return $durationAvailable;
        }
        return -1;
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
