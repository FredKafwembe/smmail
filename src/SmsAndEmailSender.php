<?php

require_once __DIR__ . "\\..\\vendor\\autoload.php";

$smsSender = new SmsSender();
$smsSender->setSendersName("Fred");
$smsSender->setMessage("First test of many");
$smsSender->addReceiverPhoneNumber("0974223613", "ZM");
$smsSender->addReceiverPhoneNumber("+260 97 1503354");
//$smsSender->sendSms();

$emailSender = new EmailSender();
//$emailSender->addEmail("fredjkafwembe@gmail.com");
//print(__DIR__ . "\n");
//$emailSender->setEmailContent("This is a test email");
//$emailSender->setEmailSubject("Test email");
//$emailSender->setSenderEmail("fred.kafwembe@cs.unza.zm", "Fred");
//$emailSender->addReciverEmail("fredjkafwembe@gmail.com", "Fred");
//$emailSender->sendEmail();

class SmsAndEmailSender {

}
