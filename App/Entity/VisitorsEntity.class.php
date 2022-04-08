<?php

declare(strict_types=1);

class VisitorsEntity extends Entity
{
    /** @id */
    private int $vID;
    /** @Hits */
    private int $hits;
    /** @Ip Address @var string */
    private string $ipAddress;
    private string $cookies;
    private string $useragent;
    private string $latitude;
    private string $longitude;
    /** @var DateTimeInterface */
    private DateTimeInterface $date_enreg;
    /** @var DateTimeInterface */
    private DateTimeInterface $updateAt;
    private string $statusCode;
    private string $countryName;
    private string $countryCode;
    private string $regionName;
    private string $cityName;
    private string $zipCode;
    private string $timeZone;
    private string $regionCode;
    private string $continentCode;
    private string $continentName;

    public function __construct()
    {
        $this->date_enreg = new DateTimeImmutable();
    }

    public function getVID()
    {
        return $this->vID;
    }

    public function setVID($vID)
    {
        $this->vID = $vID;

        return $this;
    }

    public function getHits()
    {
        return $this->hits;
    }

    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getCookies()
    {
        return $this->cookies;
    }

    public function setCookies($cookies)
    {
        $this->cookies = $cookies;

        return $this;
    }

    public function getUseragent()
    {
        return $this->useragent;
    }

    public function setUseragent($useragent)
    {
        $this->useragent = $useragent;

        return $this;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getCountryName()
    {
        return $this->countryName;
    }

    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getRegionName()
    {
        return $this->regionName;
    }

    public function setRegionName($regionName)
    {
        $this->regionName = $regionName;

        return $this;
    }

    public function getCityName()
    {
        return $this->cityName;
    }

    public function setCityName($cityName)
    {
        $this->cityName = $cityName;

        return $this;
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getTimeZone()
    {
        return $this->timeZone;
    }

    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    public function getRegionCode()
    {
        return $this->regionCode;
    }

    public function setRegionCode($regionCode)
    {
        $this->regionCode = $regionCode;

        return $this;
    }

    public function getContinentCode()
    {
        return $this->continentCode;
    }

    public function setContinentCode($continentCode)
    {
        $this->continentCode = $continentCode;

        return $this;
    }

    public function getContinentName()
    {
        return $this->continentName;
    }

    public function setContinentName($continentName)
    {
        $this->continentName = $continentName;

        return $this;
    }

    public function getDate_enreg()
    {
        return $this->date_enreg;
    }
}