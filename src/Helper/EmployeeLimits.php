<?php
namespace BusinessCore\Helper;

use BusinessCore\Exception\InvalidTimeLimitsException;

class EmployeeLimits
{
    private $mondayEnabled = false;
    private $tuesdayEnabled = false;
    private $wednesdayEnabled = false;
    private $thursdayEnabled = false;
    private $fridayEnabled = false;
    private $saturdayEnabled = false;
    private $sundayEnabled = false;

    private $mondayRanges = [];
    private $tuesdayRanges = [];
    private $wednesdayRanges = [];
    private $thursdayRanges = [];
    private $fridayRanges = [];
    private $saturdayRanges = [];
    private $sundayRanges = [];

    /**
     * @param $string
     * @param $start
     * @return string
     */
    private static function getLimit($string, $start)
    {
        $end = strpos($string, ')', $start + 3);
        $length = $end - $start - 3;
        return substr($string, $start + 3, $length);
    }

    public function toString()
    {
        $string = '';
        if ($this->mondayEnabled) {
            $string .= 'mo(' . $this->stringifyRanges($this->mondayRanges) .')';
        }
        if ($this->tuesdayEnabled) {
            $string .= 'tu(' . $this->stringifyRanges($this->tuesdayRanges) .')';
        }
        if ($this->wednesdayEnabled) {
            $string .= 'we(' . $this->stringifyRanges($this->wednesdayRanges) .')';
        }
        if ($this->thursdayEnabled) {
            $string .= 'th(' . $this->stringifyRanges($this->thursdayRanges) .')';
        }
        if ($this->fridayEnabled) {
            $string .= 'fr(' . $this->stringifyRanges($this->fridayRanges) .')';
        }
        if ($this->saturdayEnabled) {
            $string .= 'sa(' . $this->stringifyRanges($this->saturdayRanges) .')';
        }
        if ($this->sundayEnabled) {
            $string .= 'su(' . $this->stringifyRanges($this->sundayRanges) .')';
        }

        return $string;
    }

    public static function fromArray(array $array)
    {
        $limits = new self();
        if (array_key_exists('mo-cb', $array) && $array['mo-cb'] === 'on') {
            $limits->mondayEnabled = true;
        }
        if (array_key_exists('tu-cb', $array) && $array['tu-cb'] === 'on') {
            $limits->tuesdayEnabled = true;
        }
        if (array_key_exists('we-cb', $array) && $array['we-cb'] === 'on') {
            $limits->wednesdayEnabled = true;
        }
        if (array_key_exists('th-cb', $array) && $array['th-cb'] === 'on') {
            $limits->thursdayEnabled = true;
        }
        if (array_key_exists('fr-cb', $array) && $array['fr-cb'] === 'on') {
            $limits->fridayEnabled = true;
        }
        if (array_key_exists('sa-cb', $array) && $array['sa-cb'] === 'on') {
            $limits->saturdayEnabled = true;
        }
        if (array_key_exists('su-cb', $array) && $array['su-cb'] === 'on') {
            $limits->sundayEnabled = true;
        }

        if ($limits->mondayEnabled && array_key_exists('mo', $array)) {
            $limits->mondayRanges = $limits->parseValidRanges($array['mo']);
        }

        if ($limits->tuesdayEnabled && array_key_exists('tu', $array)) {
            $limits->tuesdayRanges = $limits->parseValidRanges($array['tu']);
        }

        if ($limits->wednesdayEnabled && array_key_exists('we', $array)) {
            $limits->wednesdayRanges = $limits->parseValidRanges($array['we']);
        }

        if ($limits->thursdayEnabled && array_key_exists('th', $array)) {
            $limits->thursdayRanges = $limits->parseValidRanges($array['th']);
        }

        if ($limits->fridayEnabled && array_key_exists('fr', $array)) {
            $limits->fridayRanges = $limits->parseValidRanges($array['fr']);
        }

        if ($limits->saturdayEnabled && array_key_exists('sa', $array)) {
            $limits->saturdayRanges = $limits->parseValidRanges($array['sa']);
        }

        if ($limits->sundayEnabled && array_key_exists('su', $array)) {
            $limits->sundayRanges = $limits->parseValidRanges($array['su']);
        }

        return $limits;

    }

    public static function fromString($string)
    {
        //mo(09:10-10:20,19:00-20:20)tu()th(19:00-20:20)

        $limits = new self();

        $start = strpos($string, 'mo(');
        if ($start !== false) {
            $limits->mondayEnabled = true;
            $result = self::getLimit($string, $start);
            $limits->mondayRanges = $limits->parseRanges($result);
        }
        $start = strpos($string, 'tu(');
        if ($start !== false) {
            $limits->tuesdayEnabled = true;
            $result = self::getLimit($string, $start);
            $limits->tuesdayRanges = $limits->parseRanges($result);
        }
        $start = strpos($string, 'we(');
        if ($start !== false) {
            $limits->wednesdayEnabled = true;
            $result = self::getLimit($string, $start);
            $limits->wednesdayRanges = $limits->parseRanges($result);
        }
        $start = strpos($string, 'th(');

        if ($start !== false) {
            $limits->thursdayEnabled = true;
            $result = self::getLimit($string, $start);
            $limits->thursdayRanges = $limits->parseRanges($result);
        }
        $start = strpos($string, 'fr(');
        if ($start !== false) {
            $limits->fridayEnabled = true;
            $result = self::getLimit($string, $start);
            $limits->fridayRanges = $limits->parseRanges($result);
        }
        $start = strpos($string, 'sa(');
        if ($start !== false) {
            $limits->saturdayEnabled = true;
            $result = self::getLimit($string, $start);
            $limits->saturdayRanges = $limits->parseRanges($result);
        }
        $start = strpos($string, 'su(');
        if ($start !== false) {
            $limits->sundayEnabled = true;
            $result = self::getLimit($string, $start);
            $limits->sundayRanges = $limits->parseRanges($result);
        }

        return $limits;

    }

    public function parseRanges($rangesString)
    {
        $result = [];
        $ranges = explode(',', $rangesString);

        foreach ($ranges as $range) {
            $times = explode('-', $range);
            if (count($times) === 2) {
                $from = $times[0];
                $to = $times[1];
                $result[] = ['from' => $from, 'to' => $to];
            }
        }
        return $result;
    }

    private function stringifyRanges(array $ranges)
    {
        $result = [];
        foreach ($ranges as $range) {
            $result[] = $range['from'] . '-' . $range['to'];
        }
        return implode(',', $result);
    }

    /**
     * @return boolean
     */
    public function isMondayEnabled()
    {
        return $this->mondayEnabled;
    }

    /**
     * @return boolean
     */
    public function isTuesdayEnabled()
    {
        return $this->tuesdayEnabled;
    }

    /**
     * @return boolean
     */
    public function isWednesdayEnabled()
    {
        return $this->wednesdayEnabled;
    }

    /**
     * @return boolean
     */
    public function isThursdayEnabled()
    {
        return $this->thursdayEnabled;
    }

    /**
     * @return boolean
     */
    public function isFridayEnabled()
    {
        return $this->fridayEnabled;
    }

    /**
     * @return boolean
     */
    public function isSaturdayEnabled()
    {
        return $this->saturdayEnabled;
    }

    /**
     * @return boolean
     */
    public function isSundayEnabled()
    {
        return $this->sundayEnabled;
    }

    /**
     * @return array
     */
    public function getMondayRanges()
    {
        return $this->mondayRanges;
    }

    /**
     * @return array
     */
    public function getTuesdayRanges()
    {
        return $this->tuesdayRanges;
    }

    /**
     * @return array
     */
    public function getWednesdayRanges()
    {
        return $this->wednesdayRanges;
    }

    /**
     * @return array
     */
    public function getThursdayRanges()
    {
        return $this->thursdayRanges;
    }

    /**
     * @return array
     */
    public function getFridayRanges()
    {
        return $this->fridayRanges;
    }

    /**
     * @return array
     */
    public function getSaturdayRanges()
    {
        return $this->saturdayRanges;
    }

    /**
     * @return array
     */
    public function getSundayRanges()
    {
        return $this->sundayRanges;
    }

    private function parseValidRanges($range)
    {
        $limits = $this->parseRanges($range);
        $from = $limits['from'];
        $to = $limits['to'];

        if (empty($from) || empty($to) || new \DateTime($from) >= new \DateTime($to)) {
            throw new InvalidTimeLimitsException("Time from is before time to");
        }
        return $limits;
    }
}
