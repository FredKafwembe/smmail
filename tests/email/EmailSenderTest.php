<?php
/**
 * @author Fred Kafwembe
 */
use PHPUnit\Framework\TestCase;
use PharIo\Manifest\Email;

/**
 * Class used to carry out unit tests on the functionality of
 * the EmailSender class
 */
class EmailSenderTest extends TestCase {
	public function testCanAddValidReceiverEmail() {
		$emailSender = new EmailSender();
		$this->assertTrue($emailSender->addReceiverEmail("johnDoe@gmail.com"));
		return $emailSender;
	}

	public function testCannotAddInvalidReceiverEmail() {
		$this->expectException(InvalidArgumentException::class);
		$emailSender = new EmailSender();
		$emailSender->addReceiverEmail('invalid email');
	}

	/**
	 * @depends testCanAddValidReceiverEmail
	 */
	public function testCannotAddSameReceiverEmail($emailSender) {
		$this->assertFalse($emailSender->addReceiverEmail("johnDoe@gmail.com"));
	}

	/**
	 * @depends testCanAddValidReceiverEmail
	 */
	public function testCanRemoveSpecificEmail($emailSender) {
		$emailCount = $emailSender->getReceiverEmailCount();
		$emailRemoved = $emailSender->removeReceiverEmail("johnDoe@gmail.com");
		$emailCountAfter = $emailSender->getReceiverEmailCount();
		$this->assertTrue($emailCount - 1 == $emailCountAfter && $emailRemoved);
	}

	/**
	 * @depends testCanAddValidReceiverEmail
	 */
	public function testCanRemoveAllReceiverEmails($emailSender) {
//		$emailSender = new EmailSender();
		$emailSender->removeAllReceiverEmails();
		$this->assertTrue(0 == $emailSender->getReceiverEmailCount());
	}

	public function testCanAddValidSenderEmail() {
		$emailSender = new EmailSender();
		$this->assertTrue($emailSender->setSenderEmail("johnDoe@gmail.com", "John Doe"));
	}

	public function testCannotAddInvalidSenderEmail() {
		$this->expectException(InvalidArgumentException::class);
		$emailSender = new EmailSender();
		$emailSender->setSenderEmail("invalid email", "Invalid Name");
	}
}
