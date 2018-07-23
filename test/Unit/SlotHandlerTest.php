<?php

namespace Appointment\Test;

use Appointment\Attendee;
use Appointment\SlotHandler;

class SlotHandlerTest extends \PHPUnit\Framework\TestCase
{
    private $attendeeConfiguration;
    private $attendee;
    private $SlotHandler;
    private $container;
    public function setUp()
    {
        $this->container   = require __DIR__ . '/../container.php';
        $this->slotHandler = new SlotHandler();
    }

    public function testGetAvailableSlots()
    {
        $dateSelected = date('2018-07-27');

        print_r(
            $this->slotHandler->getAvailableSlots(
                $dateSelected,
                $this->container->get(Attendee::class)
            )
        );
    }
}
