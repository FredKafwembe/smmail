<?php

class SmsSender {
    private $_nexmoClient;
    private $_phoneNumberList;
    private $_message;
    private $_senderName;

    function __construct() {
        $basic  = new \Nexmo\Client\Credentials\Basic(NEXMO_API_KEY["key"], NEXMO_API_KEY["secret"]);
        $this->_nexmoClient = new \Nexmo\Client($basic);
    }

    public function addReceiverPhoneNumber($phoneNumber, $regionCode = NULL) {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($phoneNumber, $regionCode);
            $isValid = $phoneUtil->isValidNumber($numberProto);
            if($isValid) {
                $this->_phoneNumberList[] = $phoneUtil->format($numberProto, \libphonenumber\PhoneNumberFormat::E164);
            } else {
                throw new InvalidArgumentException("$phoneNumber is not a valid number");
            }
        } catch (\libphonenumber\NumberParseException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
        return true;
    }

    public function setSendersName($name) {
        $this->_senderName = $name;
    }

    public function setMessage($message) {
        $this->_message = $message;
    }

    public function sendSms() {
        foreach($this->_phoneNumberList as $phoneNumber) {
            try {
                $message = $this->_nexmoClient->message()->send([
                    'to' => $phoneNumber,
                    'from' => $this->_senderName,
                    'text' => $this->_message
                ]);

                $response = $message->getResponseData();

                if($response['messages'][0]['status'] == 0) {
                    echo "The message was sent successfully\n";
                } else {
                    echo "The message failed with status: " . $response['messages'][0]['status'] . "\n";
                }
            } catch (Exception $e) {
                echo "The message was not sent. Error: " . $e->getMessage() . "\n";
            }
        }
    }
}
