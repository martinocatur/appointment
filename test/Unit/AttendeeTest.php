<?php

namespace Appointment\Test;

use Appointment\AttendeeConfiguration;
use Appointment\Attendee;

class AttendeeTest extends \PHPUnit\Framework\TestCase
{
    private $attendee;

    private $config;

    public function setUp()
    {
        $this->config = new AttendeeConfiguration();

        $this->attendee = new Attendee(
            $this->config
        );
    }

    public function testInstanceNotNull()
    {
        $this->assertNotNull($this->attendee);
    }

    public function testListEvents()
    {
        $events = $this->attendee->makeRequest(
            $this->attendee::FETCH_LIST_EVENTS,
            $optParams = array(
                'maxResults'   => 10,
                'orderBy'      => 'startTime',
                'singleEvents' => true
            )
        );
        $this->assertNotNull($events);
    }
}
