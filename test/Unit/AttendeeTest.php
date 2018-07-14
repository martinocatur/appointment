<?php

namespace Appointment\Test;

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
        $events = $this->attendee->listEvents();

        $this->assertNotFalse($events);
    }
}
