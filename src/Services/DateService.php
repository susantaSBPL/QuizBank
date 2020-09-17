<?php declare(strict_types=1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Services;

use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

/**
 * Class DateService
 *
 * We support 3 TimeZones:
 *     1. "Server"   - The Timezone the "Server" is running in.
 *     2. "Client"   - The Timezone of the signed in User as determined by "Browser" location this can change as and
 *                     when the "Client" changes there location.
 *     3. "Practice" - The Timezone of the "Practice". This is set when the Practice is created and is immutable.
 */
class DateService
{
    private string $serverTimeZone;

    /**
     * DateService constructor.
     */
    public function __construct()
    {
        $this->serverTimeZone = date_default_timezone_get();
    }

    /**
     * @return DateTime
     *
     * @throws Exception
     */
    public function getServerDateTime(): DateTime
    {
        // MAS Ignore Exception it will never be thrown
        return new DateTime("now", new DateTimeZone($this->serverTimeZone));
    }

    /**
     * @param DateTime $dateTime
     *
     * @return DateTime
     */
    public function getDateInServerTimeZone(DateTime $dateTime)
    {
        $serverDateTime = clone $dateTime;
        $serverDateTime->setTimezone(new DateTimeZone($this->serverTimeZone));

        return $serverDateTime;
    }

    /**
     * Add a day to given date
     *
     * @param DateTime $dateTime
     *
     * @return DateTime
     *
     * @throws Exception
     */
    public function addOneDay(DateTime $dateTime): DateTime
    {
        $newDateTime = clone $dateTime;
        $newDateTime->add(new DateInterval("P1D"));

        return $newDateTime;
    }

    /**
     * Add days to given date
     *
     * @param DateTime $dateTime
     * @param int      $days
     *
     * @return DateTime
     *
     * @throws Exception
     */
    public function addDays(DateTime $dateTime, int $days): DateTime
    {
        $newDateTime = clone $dateTime;
        $newDateTime->add(new DateInterval("P".$days."D"));

        return $newDateTime;
    }

    /**
     * Given an Start Date we generate an End Date, which is effectively start date - 1 sec
     *
     * @param DateTime $startDate
     *
     * @return DateTime
     *
     * @throws Exception
     */
    public function getEndDate(DateTime $startDate)
    {
        $endDateTime = clone $startDate;
        $endDateTime->sub(new DateInterval("PT1S"));

        return $endDateTime;
    }

    /**
     * Given an End Date we generate a Start Date, which is effectively end date + 1 sec
     *
     * @param DateTime $endDate
     *
     * @return DateTime
     *
     * @throws Exception
     */
    public function getStartDate(DateTime $endDate)
    {
        $startDateTime = clone $endDate;
        $startDateTime->add(new DateInterval("PT1S"));

        return $startDateTime;
    }

    /**
     * @param string $birthDay - Format mm/dd/yyyy
     *
     * @throws Exception
     */
    public function isValidBirthDate(string $birthDay): void
    {
        $dateTime = DateTime::createFromFormat('m/d/Y', $birthDay);
        $now = new DateTime();

        // Is date in future?
        if ($dateTime > $now) {
            throw new Exception("Date cannot be in future");
        }

        $diff = $dateTime->diff($now);
        if ($diff->y > 99) {
            throw new Exception('Patient age cannot be more than 100 years');
        }
    }
}
