<?php 

namespace Appointment;

class EventConfiguration
{
    const EVENT_LOCATION = 'Pondok Blimbing Indah, Blok C6 No. 17, Jl. Raya Blimbing Indah, Polowijen, Malang, Kota Malang, Jawa Timur 65126, Indonesia';

    const EVENT_DEFAULT_ATTENDEES = [
        [
            'email'=>'martinocatur@gmail.com'
        ],
        [
            'email'=>'irma.santoso@kly.id'
        ]
    ];

    const EVENT_DEFAULT_TIMEZONE = 'Asia/Jakarta';

    protected $attendees;

    protected $location;

    protected $summary;

    protected $description;

    protected $start;

    protected $end;

    protected $entryPoints;

    /**
     * [__construct description]
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

    public function getFullConfiguration()
    {
        return  array(
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
                        array('method' => 'email', 'minutes' => 24 * 60),
                        array('method' => 'popup', 'minutes' => 30),
                      ),
                    )
                );
    }

    private function attachAttendee($email)
    {
        array_push($this->attendees, ['email' => $email]);
    }
    
    private function getDefaultAttendees()
    {
        return self::EVENT_DEFAULT_ATTENDEES;
    }
}
