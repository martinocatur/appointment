<?php

namespace Appointment\Test;

use Appointment\EventFactory;

/**
 *
 */
class EventFactoryTest extends \PHPUnit\Framework\TestCase
{
    private $factory;

    public function setUp()
    {
        $this->factory = new EventFactory();
    }
    public function testCreateEvent()
    {
        $event = $this->factory->createEvent(
            'Google I/O 2015',
            'A chance to hear more about Google\'s developer products.',
            '800 Howard St., San Francisco, CA 94103',
            array(
                'dateTime' => '2015-05-28T09:00:00-07:00',
                'timeZone' => 'America/Los_Angeles',
            ),
            array(
                'dateTime' => '2015-05-28T17:00:00-07:00',
                'timeZone' => 'America/Los_Angeles',
            ),
            array(),
            array(
                array('email' => 'lpage@example.com'),
                array('email' => 'sbrin@example.com'),
            ),
            array()

        );

        $this->assertNotNull($event);
        $this->assertEquals('Google I/O 2015', $event->getSummary());
    }
}
