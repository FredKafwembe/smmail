<?php
/**
 * Created by Fred kafwembe
 * File: EmailSender.php
 * Class: EmailSender
 */
require __DIR__ . "\\..\\..\\vendor\\autoload.php"; // If you're using Composer (recommended)
require_once __DIR__ . "\\..\\common\\config.php";

/**Reveals the functionallity needed to send emails.
 */
class EmailSender {
    private $_sendgrid;                 //sendgrid api object
    private $_receiverEmailList;        //a list of all the email addresses that will receive the email
    private $_senderEmailAddress;       //the senders email address
    private $_senderName;               //name of the sender
    private $_emailSubject;             //the email subject
    private $_isHtml;                   //boolean used to determine if the email is html or plain text
    private $_emailContent;             //the content of the email

    function __construct() {
        $this->_sendgrid = new \SendGrid(SENDGRID_API_KEY);
        $this->_emailSubject = "";
    }

    /**Adds an email to the list of reciving emails.
     * 
     * Arguments
     * email - A string for the email to be added to the reciver list
     * 
     * Returns true if email and email is successfully added to the recivers list.
     * 
     * Throws an InvalidArgumentException if the given string is not formated as an email.
     */
    public function addReciverEmail($email, $receiverName = "") {
        if($this->_validateEmail($email)) {
            $this->_receiverEmailList[] = 
                ($receiverName == "" ? array("email" => $email) : array("email" => $email, "name" => $receiverName));
        } else {
            throw new InvalidArgumentException($email . " is not a valid email.");
        }
        return true;
    }

    /**Sets the senders email address.
     * 
     * Arguments
     * email - a string to be used as the senders email
     * 
     * Returns true if email address set successfully.
     * 
     * Throws an InvalidArgumenException if the given string is not formatted as an email.
     */
    public function setSenderEmail($email, $senderName) {
        if($this->_validateEmail($email)) {
            $this->_senderEmailAddress = $email;
            $this->_senderName = $senderName;
        } else {
            throw new InvalidArgumentException($email . " is not a valid email.");
        }
        return true;
    }

    /**Sets the email subject
     * 
     * Arguments
     * subject - A string to be used as the email subject
     */
    public function setEmailSubject($subject) {
        $this->_emailSubject = $subject;
    }

    /**Set the content of the email
     * 
     * Arguments
     * content - A string to be used as the content of the email 
     *      can be plain plain text or HTML.
     * isHtml - A boolean used to determine if the content is html 
     *      or plain text. Default value is false.
     */
    public function setEmailContent($content, $isHtml = false) {
        $this->_emailContent = $content;
        $this->_isHtml = $isHtml;
    }

    /**Sends an email to all the email addresses added to the email reciver list.
     * 
     * Returns true if emails sent successfully.
     * 
     * Throws an Exception if they are no emails on the reciving email list.
     */
    public function sendEmail() {
        if(isset($this->_receiverEmailList) && count($this->_receiverEmailList) != 0) {
            echo "one or more emails added\n";
            foreach($this->_receiverEmailList as $emailDetails) {
                $email = new \SendGrid\Mail\Mail();
                $email->setFrom($this->_senderEmailAddress, $this->_senderName);
                $email->setSubject($this->_emailSubject);
                $email->addTo($emailDetails["email"], isset($emailDetails["name"]) ? $emailDetails["name"] : "");
                $email->addContent($this->_isHtml ? "text/html" : "text/plain", $this->_emailContent);
                //$email->addContent(, "<strong>and easy to do anywhere, even with PHP</strong>");
                //$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
                try {
                    $response = $this->_sendgrid->send($email);
                    print $response->statusCode() . "\n";
                    print_r($response->headers());
                    print $response->body() . "\n";
                } catch (Exception $e) {
                    echo 'Caught exception: '. $e->getMessage() ."\n";
                }
            }
        } else {
            throw new Exception("No emails added to the receivers list");
        }
        return true;
    }

    /**Validates if a given string is formatted as an email
     */
    private function _validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

/*
$email = new \SendGrid\Mail\Mail();
$email->setFrom("test@example.com", "Example User");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo("test@example.com", "Example User");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
);
$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
*/
