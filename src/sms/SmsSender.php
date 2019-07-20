<?php

class SmsSender {
    function __construct() {

    }

    public function sendSms() {
        $basic  = new \Nexmo\Client\Credentials\Basic('***REMOVED***', '***REMOVED***');
        $client = new \Nexmo\Client($basic);

        $message = $client->message()->send([
            'to' => '260974223613',
            'from' => 'Nexmo',
            'text' => 'Hello from Nexmo'
        ]);
    }
}
