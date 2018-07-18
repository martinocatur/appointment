<?php

namespace Appointment\Test;

use Appointment\Attendee;
use Appointment\AttendeeConfiguration;
use function Appointment\getAvailableSlots;
use function Appointment\tmpEventsToArray;

class FunctionTest extends \PHPUnit\Framework\TestCase
{
    private $startTime;
    private $endTime;
    private $attendeeConfiguration;
    private $attendee;

    public function setUp()
    {
        $this->attendeeConfiguration = new AttendeeConfiguration();
        $this->attendee = new Attendee($this->attendeeConfiguration);
        $this->startTime = '2018-07-09T14:30:00+07:00';
        $this->endTime = '2018-07-09T15:30:00+07:00';
    }

    public function testCheckIfSlotIsAvailable()
    {
        $config = $this->attendeeConfiguration->getDateSlots();
        $calendarId = $this->attendeeConfiguration->getCalendarId();
        $startTime = $this->startTime;
        $endTime = $this->endTime;

        $options = array(
            'maxResults'   => 10,
            'orderBy'      => 'startTime',
            'singleEvents' => true,
            'timeMin'      => '2018-07-09T10:00:00+07:00',
            'timeMax'      => '2018-07-09T17:00:00+07:00'
        );

        $events = $this->attendee->listEvents($calendarId, $options);
        $this->assertTrue(!empty(getAvailableSlots($startTime, $endTime, $config, $events)));
    }
}
