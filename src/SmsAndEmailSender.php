<?php

require_once("email/EmailSender.php");

$emailSender = new EmailSender();
//$emailSender->addEmail("fredjkafwembe@gmail.com");
//print(__DIR__ . "\n");
//$emailSender->setEmailContent("This is a test email");
//$emailSender->setEmailSubject("Test email");
//$emailSender->setSenderEmail("fred.kafwembe@cs.unza.zm", "Fred");
//$emailSender->addReciverEmail("fredjkafwembe@gmail.com", "Fred");
$emailSender->sendEmail();

class SmsAndEmailSender {

}
