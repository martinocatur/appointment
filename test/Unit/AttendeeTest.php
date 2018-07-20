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

    private $container;

    public function setUp()
    {
        $this->container = require __DIR__ . '/../container.php';

        $this->attendee = $this->container->get(Attendee::class);

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
}
