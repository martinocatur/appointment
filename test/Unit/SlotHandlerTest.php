<?php

namespace Appointment\Test;

use Appointment\AttendeeConfiguration;
use Appointment\Attendee;
use Appointment\SlotHandler;

class SlotHandlerTest extends \PHPUnit\Framework\TestCase
{
    private $start;
    private $end;
    private $attendeeConfiguration;
    private $attendee;
    private $SlotHandler;

    public function setUp()
    {
        $this->attendeeConfiguration = new AttendeeConfiguration();
        $this->attendee = new Attendee($this->attendeeConfiguration);
        $this->slotHandler = new SlotHandler();
        $this->start = new \DateTime('2018-07-10T14:30:00+07:00');
        $this->end = new \DateTime('2018-07-10T15:30:00+07:00');
    }

    public function testGetAvailableSlots()
    {
        $config = $this->attendeeConfiguration->getDateSlots();
        $calendarId = $this->attendeeConfiguration->getCalendarId();
        
        $options = array(
            'maxResults'   => 10,
            'orderBy'      => 'startTime',
            'singleEvents' => true,
            'timeMin'      => '2018-07-10T06:00:00+07:00',
            'timeMax'      => '2018-07-10T18:00:00+07:00'
        );

        $slotsOnConfig = $this->slotHandler->getSlotAvailableOnConfig($this->start, $config);

        $events = $this->attendee->listEvents($calendarId, $options);
        $bookedSlots = $this->slotHandler->getBookedSlotsFromEvents($events);

        $availableSlots = $this->slotHandler->getAvailableSlots($slotsOnConfig[0], $events, $this->start, $this->end);
        $this->assertTrue(!empty($availableSlots));
    }
}
