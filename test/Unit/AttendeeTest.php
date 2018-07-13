<?php

namespace Appointment\Test;

use Appointment\Attendee;

class MakerTest extends \PHPUnit\Framework\TestCase
{
    private $attendee;

    public function setUp()
    {
        $this->attendee = new Attendee();
    }

    public function testInstanceNotNull()
    {
        $this->assertNotNull($this->attendee);
    }
}
