<?php

namespace Appointment\Test;

use Appointment\AttendeeConfiguration;
use Appointment\Attendee;
use Appointment\SlotHandler;

class AttendeeTest extends \PHPUnit\Framework\TestCase
{
    private $attendee;

    private $config;

    private $events;

    public function setUp()
    {
        $this->config = new AttendeeConfiguration();

        $this->attendee = new Attendee(
            $this->config
        );

        $this->events = $this->attendee->makeRequest(
            $this->attendee::FETCH_LIST_EVENTS,
            $optParams = array(
                'orderBy'      => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c')
            )
        );
    }

    public function testInstanceNotNull()
    {
        $this->assertNotNull($this->attendee);
    }

    public function testListEvents()
    {
        $this->assertNotNull($this->events);
    }

    public function testGetAvailableSlots()
    {
        $SlotHandler = new SlotHandler();

        $schedules = $SlotHandler->getAvailableSlots(
            20,
            $this->config->getDateSlots()[0]['monday'],
            $this->events
        );

        //print_r($schedules);
    }
}
