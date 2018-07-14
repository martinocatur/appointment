<?php

namespace Appointment;

/**
 * Filter string
 * @param  mixed $str
 * @return string
 * @throws Exception
 */
function filterString($str)
{
    if (is_string($str)) {
        return $str;
    }
    throw new \Exception("Not a string", 1);
}
/**
 * Filter file path
 * @param  string $path
 * @return string
 * @throws \Exception
 */
function filterFilePath($path)
{
    if (!file_get_contents($path)) {
        throw new \Exception("File not found", 1);
    }
    return $path;
}
/**
 * [isSlotAvailable description]
 * @param  string  $startTime
 * @param  string  $endTime
 * @return boolean
 */
function isSlotAvailable($startTime, $endTime)
{
    $dateConfigs = loadConfiguration('configuration.json');
    $date = date_create($startTime);
    $day = strtolower((date_format($date, "l")));
    $startHours = substr($startTime, 11, 5);
    $endHours = substr($endTime, 11, 5);

    $slotsOnConfig = array_column($dateConfigs['available_slots'], $day);
    $eventsOnGCal = getEvents($startTime, $endTime);

    if (!empty($slotsOnConfig)) {
        if (in_array($startHours." - ".$endHours, $slotsOnConfig[0]) && $eventsOnGCal->count()==0) {
            return true;
        }
    }

    return false;
}

function getEvents($startTime, $endTime)
{
    $client = getClient();
    $service = new \Google_Service_Calendar($client);
    $calendarId = 'primary';

    $optParams = array(
      'maxResults' => 10,
      'orderBy' => 'startTime',
      'singleEvents' => true,
      'timeMin' => date_format(date_create($startTime), "c"),
      'timeMax' => date_format(date_create($endTime), "c"),
    );

    return $service->events->listEvents($calendarId, $optParams);
}

/**
 * [loadConfiguration description]
 * @param  string $file filename
 * @return mixed
 */
function loadConfiguration($file)
{
    $confPath = getcwd() . '/' . $file;

    if (file_exists($confPath)) {
        $config = json_decode(file_get_contents($confPath), true);

        return $config;
    }

    throw \Exception('Configuration file not found');
}

function getClient()
{
    $credentialsPath = getcwd().'/credentials/';

    $client = new \Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(\Google_Service_Calendar::CALENDAR);

    $client->setAuthConfig($credentialsPath.'client_secret.json');
    $client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $accessToken = json_decode(file_get_contents($credentialsPath.'credentials.json'), true);
    $client->setAccessToken($accessToken);
    
    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath.'credentials.json', json_encode($client->getAccessToken()));
    }

    return $client;
}
