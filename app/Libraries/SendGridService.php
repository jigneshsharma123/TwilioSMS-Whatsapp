<?php

namespace App\Libraries;

use SendGrid;
use SendGrid\Mail\Mail;
use Config\SendGrid as SendGridConfig;

class SendGridService
{
    protected $sendGrid;

    public function __construct()
    {
        $sendGridConfig = new SendGridConfig();
        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CAINFO => APPPATH . 'Config/cacert.pem', // Ensure this path is correct
            ],
        ];
        $this->sendGrid = new SendGrid($sendGridConfig->apiKey, null, $options);
    }

    public function sendEmail($to, $subject, $body)
    {
        $sendGridConfig = new SendGridConfig();

        $email = new Mail();
        $email->setFrom($sendGridConfig->fromEmail, $sendGridConfig->fromName);
        $email->setSubject($subject);
        $email->addTo($to);
        $email->addContent("text/plain", $body);
        $email->addContent("text/html", $body);

        try {
            $response = $this->sendGrid->send($email);
            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                return true;
            } else {
                throw new \Exception("Failed to send email: " . $response->body());
            }
        } catch (\Exception $e) {
            log_message('error', 'SendGrid Error: ' . $e->getMessage());
            throw new \Exception("Could not send email: " . $e->getMessage());
        }
    }
}
