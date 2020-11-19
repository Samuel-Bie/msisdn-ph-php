<?php
namespace Samuelbie\MzMsisdn\Validation;

use Samuelbie\MzMsisdn\Rules\ValidMSISDN;
use Illuminate\Validation\Validator as IlluminateValidator;

class ValidatorExtended extends IlluminateValidator
{
    private $_custom_messages = array(
        "msisdn"            => 'The :attribute is not a valid Mozambique number.',
        "msisdn_vodacom"    => 'The :attribute is not a valid Vodacom number.',
        "msisdn_movitel"    => 'The :attribute is not a valid Movitel number.',
        "msisdn_tmcel"      => 'The :attribute is not a valid TMcel number.',
    );

    public function __construct($translator, $data, $rules, $messages = array(), $customAttributes = array())
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
        $this->_set_custom_stuff();
    }

    /**
     * Setup any customizations etc
     *
     * @return void
     */
    protected function _set_custom_stuff()
    {
        $this->setCustomMessages($this->_custom_messages);
    }


    protected function validateMsisdn($attribute, $value){
        return (new ValidMSISDN())->passes($attribute, $value);
    }
    protected function validateMsisdnVodacom($attribute, $value){
        return (new ValidMSISDN('vodacom'))->passes($attribute, $value);
    }
    protected function validateMsisdnTmcel($attribute, $value){
        return (new ValidMSISDN('tmcel'))->passes($attribute, $value);
    }
    protected function validateMsisdnMovitel($attribute, $value){
        return (new ValidMSISDN('movitel'))->passes($attribute, $value);
    }
}
