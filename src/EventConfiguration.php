<?php 

namespace Appointment;

class EventConfiguration
{
    /**
     * Location of the event
     */
    const EVENT_LOCATION = 'Pondok Blimbing Indah, Blok C6 No. 17, Jl. Raya Blimbing Indah, Polowijen, Malang, Kota Malang, Jawa Timur 65126, Indonesia';
    /**
     * Default attendee
     */
    const EVENT_DEFAULT_ATTENDEES = [
        [
            'email'=>'ppl@kly.id'
        ],
        [
            'email'=>'ppl1@kly.id'
        ]
    ];
    /**
     * Default Timezone
     */
    const EVENT_DEFAULT_TIMEZONE = 'Asia/Jakarta';
    /**
     * Attendees
     * @var array
     */
    protected $attendees;
    /**
     * Location
     * @var string
     */
    protected $location;
    /**
     * Summary of the event
     * @var string
     */
    protected $summary;
    /**
     * Description
     * @var string
     */
    protected $description;
    /**
     * Event's Start Time (RFC Format)
     * @var string
     */
    protected $start;
    /**
     * Event's End Time (RFC Format)
     * @var string
     */
    protected $end;
    /**
     * Entry Points
     * @var Array
     */
    protected $entryPoints;
    /**
     * Default constructor
     * @param string $start       RFC date format
     * @param string $end         RFC date format
     * @param string $attendee    email
     * @param array  $entryPoints
     * @param string $summary     Title of the evet
     * @param string $description
     * @param string $location
     */
    public function __construct(
        $start,
        $end,
        $attendee,
        $summary = '',
        $description = '',
        $location = self::EVENT_LOCATION
    ) {
        $this->summary = $summary;

        $this->description = $description;

        $this->location = $location;

        $this->attendees = $this->getDefaultAttendees();
        $this->attachAttendee($attendee);

        $this->start = [
            'dateTime' => $start,
            'timeZone' => self::EVENT_DEFAULT_TIMEZONE
        ];

        $this->end = [
            'dateTime' => $end,
            'timeZone' => self::EVENT_DEFAULT_TIMEZONE
        ];
    }

    /**
     * Get event configuration
     * @return array
     */
    public function getFullConfiguration()
    {
        return array(
            'summary' => $this->summary,
            'location' => $this->location,
            'description' => $this->description,
            'start' => $this->start,
            'end' => $this->end,
            'attendees' => $this->attendees,
            'recurrence' => '1',
            'reminders' => array(
                'useDefault' => false,
                'overrides' => array(
                    array(
                        'method' => 'email',
                        'minutes' => 1440//24 * 60
                    ),
                    array(
                        'method' => 'popup',
                        'minutes' => 30
                    ),
                ),
            )
        );
    }
    /**
     * Attach attendee to Event
     * @param  string $email
     * @return void
     */
    private function attachAttendee($email)
    {
        array_push($this->attendees, ['email' => $email]);
    }
    /**
     * Get default attendees
     * @return array
     */
    private function getDefaultAttendees()
    {
        return self::EVENT_DEFAULT_ATTENDEES;
    }
}
