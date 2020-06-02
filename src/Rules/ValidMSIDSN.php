<?php
namespace samuelbie\mzmsisdn\Rules;

use Illuminate\Contracts\Validation\Rule;
use samuelbie\mzmsisdn\Msisdn;

class ValidMSIDSN implements Rule
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
            return Msisdn::validate($value) ? true : false;

        $number = new Msisdn($value);
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
        return 'The :attribute is not a valid mozambican number.';
    }
}
