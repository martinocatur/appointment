<?php

namespace Appointment;

/**
 * Event factory
 */
class EventFactory
{
    /**
     * Create event
     * @param  string $summary     [description]
     * @param  string $description [description]
     * @param  string $location    [description]
     * @param  array  $start       [description]
     * @param  array  $end         [description]
     * @param  array  $recurrence  [description]
     * @param  array  $attendees   [description]
     * @param  array  $reminders   [description]
     * @return \Google_Service_Calendar_Event
     */
    public function createEvent(
        $summary = '',
        $description = '',
        $location = '',
        $start = array(),
        $end = array(),
        $recurrence = array(),
        $attendees = array(),
        $reminders = array()
    ) {
        return new \Google_Service_Calendar_Event(array(
            'summary'     => $summary,
            'location'    => $location,
            'description' => $description,
            'start'       => $start,
            'end'         => $end,
            'recurrence'  => $recurrence,
            'attendees'   => $attendees,
            'reminders'   => $reminders,
        ));
    }
}
