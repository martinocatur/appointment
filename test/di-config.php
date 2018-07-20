<?php

use function DI\create;
use function DI\autowire;
use Appointment\AttendeeConfiguration;
use Appointment\Attendee;

return [
     Attendee::class => autowire(Attendee::class)
];
