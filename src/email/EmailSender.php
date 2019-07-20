<?php

/** Reveals the functionallity needed to send emails. */
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

    /** Adds an email to the list of receiving emails.
     * 
     * @param string $email The email that is going to be added to the receiver list.
     * @param string $receiverName The name of the receiver.
     * 
     * @throws InvalidArgumentException if the given string is not formated as an email.
     * 
     * @return boolean Indicates if the email was added successfully.
     */
    public function addReceiverEmail($email, $receiverName = "") {
        if($this->_validateEmail($email)) {
            $this->_receiverEmailList[] = 
                ($receiverName == "" ? array("email" => $email) : array("email" => $email, "name" => $receiverName));
        } else {
            throw new InvalidArgumentException($email . " is not a valid email.");
        }
        return true;
    }

    /** Sets the senders email address.
     * 
     * @param string $email The senders email.
     * @param string $senderName The name of the sender.
     * 
     * @throws InvalidArgumenException if the given string is not formatted as an email.
     * 
     * @return boolean Indicates if the senders email address was set successfully.
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

    /** Sets the email subject
     * 
     * @param string $subject The subject of the email.
     */
    public function setEmailSubject($subject) {
        $this->_emailSubject = $subject;
    }

    /** Set the content of the email
     * 
     * @param string $content The content of the email can be plain plain text or HTML.
     * @param boolean $isHtml Determines if the content is html or plain text.
     */
    public function setEmailContent($content, $isHtml = false) {
        $this->_emailContent = $content;
        $this->_isHtml = $isHtml;
    }

    /** Sends an email to all the email addresses added to the email reciver list.
     * 
     * @throws Exception If they are no emails on the reciving email list.
     * @throws Exception If fails to send an email.
     * 
     * @return boolean Indicates if the emails were sent successfully.
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
                    throw new Exception("Failed to send email: " . $e->getMessage());
                }
            }
        } else {
            throw new Exception("No emails added to the receivers list");
        }
        return true;
    }

    /** Validates if a given string is formatted as an email
     * 
     * @param string $email The string to be validated as an email.
     */
    private function _validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
