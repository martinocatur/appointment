<?php

namespace Appointment\Test;

use Appointment\Attendee;

class MakerTest extends \PHPUnit\Framework\TestCase
{
    private $attendee;

    public function setUp()
    {
        $credential = \loadCredential();

        $this->attendee = new Attendee(
            $credential['client_secret'],
            $credential['oauth']
        );
    }

    public function testInstanceNotNull()
    {
        $this->assertNotNull($this->attendee);
    }

    public function testListEvents()
    {
        $events = $this->attendee->listEvents();

        print_r($events);
    }
}
