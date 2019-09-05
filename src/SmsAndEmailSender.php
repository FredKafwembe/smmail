<?php

require_once __DIR__ . "\\..\\vendor\\autoload.php";
//require_once __DIR__ . "\\common\\Config.php";

//$smsSender = new SmsSender();
//$smsSender->setSendersName("Fred");
//$smsSender->setMessage("First test of many");
//$smsSender->addReceiverPhoneNumber("0974223613", "ZM");
//$smsSender->addReceiverPhoneNumber("+260 97 1503354");
//$smsSender->sendSms();

$dbConn = DBConnector::instance();

$emailSender = new EmailSender();
//print(__DIR__ . "\n");
//$emailSender->setEmailContent("This is a test email");
//$emailSender->setEmailSubject("Test email");
//$emailSender->setSenderEmail("fred.kafwembe@cs.unza.zm", "Fred");
$emailSender->addReceiverEmail("fredjkafwembe@gmail.com", "Fred");
$emailSender->addReceiverEmail("fredjkafwemb@gmail.com", "Fred");
//print($emailSender->removeReceiverEmail("fredjkafwemb@gmail.com") ? "removed\n" : "not removed\n");
//$emailSender->sendEmail();

//print(("revolve" == "revolve") ? "true" : "false");

class SmsAndEmailSender {

}
