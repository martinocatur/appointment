<?php

namespace Appointment\Test;

use Appointment\AttendeeConfiguration;
use Appointment\Attendee;
use Appointment\SlotHandler;
use Appointment\EventConfiguration;

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
  
    public function testInsertEvent()
    {
        $description = "Hai,\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

        $summary = 'First Direct Interview';

        $attendee = 'catureezy@gmail.com';
        $start = '2018-07-27T10:00:00+07:00';
        $end = '2018-07-27T11:00:00+07:00';

        $eventConfiguration = new EventConfiguration($start, $end, $attendee, $summary, $description);

        $eventTobeCreated = new \Google_Service_Calendar_Event($eventConfiguration->getFullConfiguration());

        $calendarId = 'primary';
        $eventCreated = $this->attendee->submitEvent($calendarId, $eventTobeCreated);

        $optParams = array(
                'orderBy'      => 'startTime',
                'singleEvents' => true,
                'timeMin' => $start,
                'timeMax' => $end
            );

        $events = $this->attendee->listEvents($calendarId, $optParams);
        
        $this->assertEquals(1, count($events));

        $this->assertEquals($events[0]->getSummary(), $summary);

        $this->assertTrue(!is_null($events[0]->getConferenceData()));
    }
}
