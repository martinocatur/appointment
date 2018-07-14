<?php

namespace Appointment;

/**
 * Google Api Client
 */
class Attendee
{
    const CLIENT_SECRET    = 'client_secret.json';
    const CREDENTIAL_FILE  = 'credentials.json';
    const APPLICATION_NAME = 'Interview App';
    const ACCESSS_TYPE     = 'offline';

    private $googleClient;
    
    private $googleService;
    /**
     * Constructor
     * @param array $clientSecret
     * @param array $oauth
     */
    public function __construct(
        $clientSecret = array(),
        $oauth = array()
    ) {
        $this->googleClient = new \Google_Client();
        $this->googleClient->setApplicationName(self::APPLICATION_NAME);
        $this->googleClient->setScopes(\Google_Service_Calendar::CALENDAR);
        $this->googleClient->setAuthConfig($clientSecret);
        $this->googleClient->setAccessType(self::ACCESSS_TYPE);
        $this->googleClient->setAccessToken($oauth);
        $this->refreshToken();
        $this->googleService = new \Google_Service_Calendar(
            $this->googleClient
        );
    }
    /**
     * List events based on calendarID
     * @return array
     */
    public function listEvents()
    {
        $calendarId = 'primary';
        
        $optParams  = array(
            'maxResults'   => 10,
            'orderBy'      => 'startTime',
            'singleEvents' => true,
            'timeMin'      => date('c'),
        );

        $results = $this->googleService->events->listEvents($calendarId, $optParams);
        
        $events = array();

        foreach ($results->getItems() as $event) {
            array_push($events, $event->getSummary());
        }

        return $events;
    }
    /**
     * Get google client
     * @return \Google_Client
     */
    public function getClient()
    {
        $client = clone $this->googleClient;

        return $client;
    }
    /**
     * Refresh token each time new object created
     * @return void
     */
    private function refreshToken()
    {
        if ($this->googleClient->isAccessTokenExpired()) {
            $this->googleClient->fetchAccessTokenWithRefreshToken(
                $this->googleClient->getRefreshToken()
            );
        }
    }
}
