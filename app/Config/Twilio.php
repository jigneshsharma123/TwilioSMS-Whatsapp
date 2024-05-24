<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Twilio extends BaseConfig
{
    public $account_sid;
    public $auth_token;
    public $twilio_number = '+14793515439';
    public $twilio_whatsapp_number = '+14155238886';

    public function __construct()
    {
        parent::__construct();

        $this->account_sid = getenv('TWILIO_ACCOUNT_SID');
        $this->auth_token = getenv('TWILIO_AUTH_TOKEN');
    }
}
