<?php

namespace Appointment\Test;

use function Appointment\loadConfiguration;
use function Appointment\isSlotAvailable;
use function Appointment\getEvents;

class FunctionTest extends \PHPUnit\Framework\TestCase
{

	private $startTime;
	private $endTime;

	public function setUp()
	{
		$this->startTime = '2018-07-12T10:00:00+07:00';
		$this->endTime = '2018-07-12T11:00:00+07:00';
	}

    public function testCheckIfSlotIsAvailable()
    {
    	$this->assertTrue(isSlotAvailable($this->startTime, $this->endTime));
    }

    public function testGetEvents()
    {
    	$results = getEvents($this->startTime, $this->endTime);
    	$this->assertNotNull($results);
    }
}
