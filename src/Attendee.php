<?php

namespace Appointment;

use Appointment\AttendeeConfiguration;

/**
 * Google Api Client
 */
class Attendee
{
    private $googleClient;

    private $googleService;

    private $config;

    private $events;

    const FETCH_LIST_EVENTS = 'list_events';

    /**
     * Consturctor
     * @param AttendeeConfiguration $config
     */
    public function __construct(AttendeeConfiguration $config)
    {
        $this->config = $config;

        $this->initState();
    }
    /**
     * Init Client and Service State
     * @return void
     */
    private function initState()
    {
        $this->googleClient = new \Google_Client();
        $this->googleClient->setApplicationName(
            $this->config->getApplicationName()
        );
        $this->googleClient->setScopes(
            \Google_Service_Calendar::CALENDAR
        );
        $this->googleClient->setAuthConfig(
            $this->config->getClientSecret()
        );
        $this->googleClient->setAccessType(
            $this->config->getAccessType()
        );
        $this->googleClient->setAccessToken(
            $this->config->getOauth()
        );
        $this->refreshToken();
        $this->googleService = new \Google_Service_Calendar(
            $this->googleClient
        );
    }
    /**
     * Make request to google api
     * @param  string $type
     * @param  array $options
     * @return mixed
     */
    public function makeRequest($type = '', $options = array())
    {
        switch ($type) {
            case self::FETCH_LIST_EVENTS:
                return
                    $this->listEvents(
                        $this->filterCalendarId(
                            $this->config->getCalendarId()
                        ),
                        $options
                    );
            default:
                throw new \Exception("Type undefined", 1);

        }
    }
    /**
     * List events based on calendarID
     * @param  string $startTime string date('c') format
     * @param  string $endTime string date('c') format
     * @return \Google_Service_Calendar_Event
     * @throws \Google_Service_Exception
     */
    public function listEvents($calendarId, $options = array())
    {
        $results = $this->googleService->events->listEvents(
            $calendarId,
            $options
        );

        return $results->getItems();
    }
    /**
     * Get configuration
     * @return AttendeeConfiguration
     */
    public function getConfig()
    {
        return $this->config;
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
    /**
     * Filter calendar id
     * @param  string $calendarId
     * @return string
     * @throws \Exception
     */
    private function filterCalendarId($calendarId)
    {
        if (!isset($calendarId) && is_null($calendarId)) {
            throw new \Exception("Calendar Id required", 1);
        }
        return $calendarId;
    }
}
