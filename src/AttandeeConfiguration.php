<?php

namespace Appointment;

use function Appointment\filterFilePath;

/**
 *
 */
class AttandeeConfiguration
{
    private $applicationName;

    private $calendarId;

    private $accessType;

    private $clientSecret;

    private $oauth;

    private $dateSlots;

    const CONFIGURATION_FILE = 'config.json';

    const CREDENTIAL_FILE = 'credential.json';

    /**
     * Constructor
     */
    public function __construct($args = null)
    {
        if (is_null($args)) {
            $this->setupAppSettings();

            $this->setupCredentials();
        }
    }
    /**
     * Get application name
     * @return string
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }
    /**
     * Get calendar Id
     * @return string
     */
    public function getCalendarId()
    {
        return $this->calendarId;
    }
    /**
     * Get access type
     * @return string
     */
    public function getAccessType()
    {
        return $this->accessType;
    }
    /**
     * Get client Secret
     * @return array
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }
    /**
     * Get oauth
     * @return array
     */
    public function getOauth()
    {
        return $this->oauth;
    }
    /**
     * Get date slots on config file
     * @return array
     */
    public function getDateSlots()
    {
        return $this->dateSlots;
    }
    /**
     * Get application settings
     * @return array
     */
    private function setupAppSettings()
    {
        $appSettings = json_decode(
            file_get_contents(
                filterFilePath(getcwd() . '/' . self::CONFIGURATION_FILE)
            ),
            true
        );

        $this->applicationName = $appSettings['application_name'];

        $this->calendarId = $appSettings['calendar_id'];

        $this->accessType = $appSettings['access_type'];

        $this->dateSlots = $appSettings['available_slots'];
    }
    /**
     * get credential
     * @return void
     */
    private function setupCredentials()
    {
        $credentials = json_decode(
            file_get_contents(
                filterFilePath(getcwd() . '/' . self::CREDENTIAL_FILE)
            ),
            true
        );

        $this->clientSecret = $credentials['client_secret'];

        $this->oauth = $credentials['oauth'];
    }
}
