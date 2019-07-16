<?php

use PHPUnit\Framework\TestCase;

class EmailSenderTest extends TestCase {
  public function testCanAddReciverEmail() {
    $emailSender = new EmailSender();
    $this->assertEquals(true, $emailSender->addReciverEmail("johnDoe@gmail.com"));
  }

  public function testCannotAddReciverEmail() {
    $this->expectException(InvalidArgumentException::class);
    EmailSender::addReciverEmail('invalid');
  }
}
