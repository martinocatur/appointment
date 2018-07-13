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
    const ACESSS_TYPE      = 'offline';

    private $googleClient;

    private $email;
    /**
     * Default constructor
     * @param string $credentialFile
     * @param string $applicationName
     * @param string $scope
     * @param string $accessType
     * @param string $clientSecretFile
     */
    public function __construct(
        $applicationName = self::APPLICATION_NAME,
        $scope = \Google_Service_Calendar::CALENDAR,
        $accessType = self::ACESSS_TYPE,
        $clientSecretFile = ''
    ) {
        $this->googleClient = new \Google_Client();
        $this->googleClient->setApplicationName($applicationName);
        $this->googleClient->setScopes($scope);
        $this->googleClient->setAuthConfig(
                $this->filterCredentialPath(
                    $clientSecretFile
                )
        );
        $this->googleClient->setAccessType($accessType);
    }

    /**
     * Check if path exist
     * @param  string $path
     * @return string
     */
    public function filterCredentialPath($path)
    {
        if (!file_exists($path)) {
            return realpath(__DIR__ . '/../' . self::CLIENT_SECRET);
        }
        return $path;
    }

    /**
     * Create array with key =>'email'
     * @return array
     */
    private function serializeEmail()
    {
        return array(
            'email'=>$this->email
        );
    }
}
