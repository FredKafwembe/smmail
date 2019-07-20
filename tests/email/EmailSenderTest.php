<?php
/**
 * Created by Fred Kafwembe
 * File: EmailSenderTest.php
 * Class: EmailSenderTest
 * Parent class: TestCase
 */
use PHPUnit\Framework\TestCase;

/**Class used to carry out unit tests on the functionality of
 * the EmailSender class
 */
class EmailSenderTest extends TestCase {
  public function testCanAddValidReciverEmail() {
    $emailSender = new EmailSender();
    $this->assertTrue($emailSender->addReciverEmail("johnDoe@gmail.com"));
  }

  public function testCannotAddInvalidReciverEmail() {
    $this->expectException(InvalidArgumentException::class);
    $emailSender = new EmailSender();
    $emailSender->addReciverEmail('invalid email');
  }

  public function testCanAddValidSenderEmail() {
    $emailSender = new EmailSender();
    $this->assertTrue($emailSender->setSenderEmail("johnDoe@gmail.com"));
  }

  public function testCannotAddInvalidSenderEmail() {
    $this->expectException(InvalidArgumentException::class);
    $emailSender = new EmailSender();
    $emailSender->setSenderEmail("invalid email");
  }
}
