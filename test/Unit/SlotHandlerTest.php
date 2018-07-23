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

    public function testGetAvailableSlotsWithValidDate()
    {
        $dateSelected = date('2018-07-27');

        $availableSlot = $this->slotHandler->getAvailableSlots($dateSelected, $this->container->get(Attendee::class));

        $this->assertTrue(!empty($availableSlot));
    }
}
