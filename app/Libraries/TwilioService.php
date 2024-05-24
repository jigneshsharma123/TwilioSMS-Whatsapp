<?php

namespace App\Libraries;

use Twilio\Rest\Client;
use Config\Twilio as TwilioConfig;
use Twilio\Exceptions\EnvironmentException;
use Twilio\Exceptions\RestException;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $twilioConfig = new TwilioConfig();
        $this->client = new Client($twilioConfig->account_sid, $twilioConfig->auth_token);
        $this->client->setHttpClient(new \Twilio\Http\CurlClient([
            CURLOPT_CAINFO => APPPATH . 'Config/cacert.pem',
        ]));
    }

    public function sendSMS($to, $message)
    {
        try {
            $twilioConfig = new TwilioConfig();
            $this->client->messages->create($to, [
                'from' => $twilioConfig->twilio_number,
                'body' => $message
            ]);
        } catch (EnvironmentException $e) {
            log_message('error', 'Environment error: ' . $e->getMessage());
            throw new \Exception('Could not send SMS: ' . $e->getMessage());
        } catch (RestException $e) {
            log_message('error', 'REST error: ' . $e->getMessage());
            throw new \Exception('Could not send SMS: ' . $e->getMessage());
        }
    }

    public function sendWhatsApp($to, $message)
    {
        try {
            $twilioConfig = new TwilioConfig();
            $from = 'whatsapp:' . $twilioConfig->twilio_whatsapp_number;
            $to = 'whatsapp:' . $to;

            $message = $this->client->messages->create($to, [
                'from' => $from,
                'body' => $message
            ]);

            log_message('info', 'WhatsApp Message SID: ' . $message->sid);
            log_message('info', 'WhatsApp Message Status: ' . $message->status);
            log_message('info', 'WhatsApp Message Sent to: ' . $to);

            return $message;
        } catch (EnvironmentException $e) {
            log_message('error', 'Environment error: ' . $e->getMessage());
            throw new \Exception('Could not send WhatsApp message: ' . $e->getMessage());
        } catch (RestException $e) {
            log_message('error', 'REST error: ' . $e->getMessage());
            throw new \Exception('Could not send WhatsApp message: ' . $e->getMessage());
        }
    }
}
