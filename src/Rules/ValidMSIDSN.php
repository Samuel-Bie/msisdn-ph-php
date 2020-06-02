<?php

namespace samuelbie\mzmsisdn\Rules;

use Exception;
use samuelbie\mzmsisdn\Msisdn;
use Illuminate\Contracts\Validation\Rule;

class ValidMSISDN implements Rule
{
    private $network = null;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($network = null)
    {
        $this->network = $network;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_null($this->network))
            try {
                return Msisdn::validate($value) ? true : false;
            } catch (Exception $e) {
                return false;
            }

        $number = null;
        try {
            $number = new Msisdn($value);
        } catch (Exception $e) {
            return false;
        }

        if ($this->network == 'tmcel') {
            return $number->isTmcel();
        }
        if ($this->network == 'vodacom') {
            return $number->isVodacom();
        }
        if ($this->network == 'movitel') {
            return $number->isMovitel();
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $target = $this->network ?? 'mozambican';

        return 'The :attribute is not a valid ' . $target . ' number.';
    }
}
