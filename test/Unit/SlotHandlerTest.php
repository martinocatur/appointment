<?php

namespace Appointment\Test;

use Appointment\Attendee;
use Appointment\AttendeeConfiguration;
use Appointment\SlotHandler;

class SlotHandlerTest extends \PHPUnit\Framework\TestCase
{
    private $attendeeConfiguration;
    private $attendee;
    private $SlotHandler;

    public function setUp()
    {
        $this->attendeeConfiguration = new AttendeeConfiguration();
        $this->attendee              = new Attendee($this->attendeeConfiguration);
        $this->slotHandler           = new SlotHandler();
    }

    public function testGetAvailableSlots()
    {
        $dateSelected = date('2018-07-27');
        
        print_r(
            $this->slotHandler->getAvailableSlots($dateSelected, $this->attendee)
        );
    }
}
