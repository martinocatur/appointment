<?php

namespace Appointment\Test;

use Appointment\AttandeeConfiguration;
use Appointment\Attendee;
use Appointment\AttandeeConfiguration;

class AttandeeTest extends \PHPUnit\Framework\TestCase
{
    private $attendee;

    private $config;

    public function setUp()
    {
        $this->config = new AttandeeConfiguration();

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
