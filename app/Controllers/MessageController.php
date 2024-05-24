<?php
// 
namespace App\Controllers;

use App\Libraries\TwilioService;


class MessageController extends BaseController
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new TwilioService();
    }

    public function sendSMS()
    {
        $to = '+917052016957';
        $message = 'Hello from CodeIgniter 4 php ';
        try {
            $this->twilio->sendSMS($to, $message);
            return "SMS sent!";
        } catch (\Exception $e) {
            log_message('error', 'Failed to send SMS: ' . $e->getMessage());
            return "Failed to send SMS: " . $e->getMessage();
        }
    }

    public function sendWhatsApp()
    {
        $to = '+917052016957';
        $message = 'Hello from CodeIgniter on WhatsApp';
        try {
            $response = $this->twilio->sendWhatsApp($to, $message);
            return "WhatsApp message sent! Message SID: " . $response->sid;
        } catch (\Exception $e) {
            log_message('error', 'Failed to send WhatsApp message: ' . $e->getMessage());
            return "Failed to send WhatsApp message: " . $e->getMessage();
        }
    }
}
