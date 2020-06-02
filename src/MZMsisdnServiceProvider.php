<?php

namespace samuelbie\mzmsisdn;

use Illuminate\Support\ServiceProvider;
use samuelbie\mzmsisdn\validation\ValidatorExtended;
class MZMsisdnServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->validator->resolver(function ($translator, $data, $rules, $messages = array(), $customAttributes = array()) {
            return new ValidatorExtended($translator, $data, $rules, $messages, $customAttributes);
        });
    }

    public function register()
    {
    }
}
