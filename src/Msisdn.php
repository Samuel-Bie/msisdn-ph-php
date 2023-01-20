<?php

namespace Samuelbie\MzMsisdn;

use Exception;
use Samuelbie\MzMsisdn\Exceptions\InvalidMsisdnException;

class Msisdn
{
    private $msisdn;

    private $mcelPrefixes = null;

    private $vodacomPrefixes = null;

    private $movitelPrefixes = null;

    private $prefix = null;

    private $operator = null;

    protected $countryPrefix = '+258';

    /**
     * Msisdn constructor.
     *
     * @param $msisdn
     * @throws InvalidMsisdnException
     */
    public function __construct($msisdn)
    {
        if (Msisdn::validate($msisdn) === false) {
            throw new InvalidMsisdnException(
                'The supplied MSISDN is not valid. ' .
                    'You can use the `Msisdn::validate()` method ' .
                    'to validate the MSISDN being passed.',
                400
            );
        }
        $this->msisdn = self::normalizeNumber($msisdn);
        $this->setPrefixes();
    }


    public function get()
    {
        return preg_replace("/[^0-9]/", "", $this->getFullNumber());
    }

    public function getFullNumber()
    {
        return $this->countryPrefix . $this->msisdn;
    }

    public function getFormatted()
    {
        return '+' . number_format($this->get(), 0, '.', ' ');
    }
    /**
     * Returns the prefix of the MSISDN number.
     *
     * @return string The prefix of the MSISDN number
     */
    public function getPrefix()
    {
        if ($this->prefix == null) {
            $this->prefix = substr($this->msisdn, 0, 2);
        }
        return $this->prefix;
    }

    /**
     * Determines the operator of this number
     *
     * @return string The operator of this number
     */
    public function getOperator()
    {
        $this->setPrefixes();

        if (!empty($this->operator)) {
            return $this->operator;
        }

        if ($this->isVodacom()) {
            $this->operator = 'VODACOM';
            return $this->operator;
        }

        if ($this->isTmcel()) {
            $this->operator = 'TMCEL';
            return $this->operator;
        }

        if ($this->isMovitel()) {
            $this->operator = 'MOVITEL';
            return $this->operator;
        }
        $this->operator = 'UNKNOWN';
        return $this->operator;
    }

    private function setPrefixes()
    {
        if (empty($this->mcelPrefixes)) {
            $this->mcelPrefixes = json_decode(file_get_contents(__DIR__ . '/Prefixes/mcel.json'));
        }

        if (empty($this->vodacomPrefixes)) {
            $this->vodacomPrefixes = json_decode(file_get_contents(__DIR__ . '/Prefixes/vodacom.json'));
        }

        if (empty($this->movitelPrefixes)) {
            $this->movitelPrefixes = json_decode(file_get_contents(__DIR__ . '/Prefixes/movitel.json'));
        }
    }

    public function isVodacom()
    {
        return (in_array($this->getPrefix(), $this->vodacomPrefixes));
    }
    public function isTmcel()
    {
        return (in_array($this->getPrefix(), $this->mcelPrefixes));
    }
    public function isMovitel()
    {
        return (in_array($this->getPrefix(), $this->movitelPrefixes));
    }


    /**
     * Validate a given mobile number
     *
     * @param string $mobileNumber
     * @return bool|string  phone number: (82|83|84|85|86|87)xxxxxxx
     */
    public static function validate($msisdn)
    {
        // Remove non digit chars
        $msisdn = preg_replace("/[^0-9]/", "", $msisdn);
        // $matchGroup array contains 5 pairs:
        // – [0] -> the full match.
        // – [1] -> starts with + or 00.
        // – [2] -> the country code 258.
        // – [3] -> the phone number without the country code. Includes the local prefix.
        // – [4] -> the local prefix, either 84 or 85.
        $matchGroup = array();
        if (preg_match('/^(\+|00)?(258)?((82|83|84|85|86|87)\d{7})$/', $msisdn, $matchGroup)) {
            return  $matchGroup[3];
        }
        return false;
    }


    /**
     * Validates and normalizes the MSISDN to 25882|3|4|5|6|7xxxxxxx as Mozambican MSISDN.
     * – Accepts MSISDN in the following formats:
     *      * (82|83|84|85|86|87)xxxxxxx
     *      * 258(82|83|84|85|86|87)xxxxxxx
     *      * +258(82|83|84|85|86|87)xxxxxxx
     *      * 00258(82|83|84|85|86|87)xxxxxxx
     *
     * @param string $msisdn msisdn which will be validated and normalized afterwards.
     * @return string normalized phone number: (82|83|84|85|86|87)xxxxxxx
     * @throws Exception
     */
    public static function normalizeNumber($msisdn)
    {
        $number = static::validate($msisdn);
        if ($number) {
            return $number;
        } else {
            throw new Exception("The provided number " . $msisdn . " is not valid Mozambican MSISDN.");
        }
    }
}
