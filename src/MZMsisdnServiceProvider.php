<?php
namespace samuelbie\mzmsisdn;

use App\Rules\ValidMSIDSN;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class MZMsisdnServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Validator::extend('msisdn', function ($attribute, $value, $parameters, $validator) {
            return (new ValidMSIDSN())->passes($attribute, $value);
        });

        Validator::extend('msisdn_vodacom', function ($attribute, $value, $parameters, $validator) {
            return (new ValidMSIDSN('vodacom'))->passes($attribute, $value);
        });
        Validator::extend('msisdn_movitel', function ($attribute, $value, $parameters, $validator) {
            return (new ValidMSIDSN('movitel'))->passes($attribute, $value);
        });
        Validator::extend('msisdn_tmcel', function ($attribute, $value, $parameters, $validator) {
            return (new ValidMSIDSN('tmcel'))->passes($attribute, $value);
        });
    }

    public function register()
    {

    }
}
