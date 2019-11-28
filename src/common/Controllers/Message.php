<?php

/**
 * 
 */
class Message extends ControllerSm {
	private $_id;
	private $_groupId;
	private $_message;
	private $_timeSent;
	private $_type;

	function __construct($message, $messageType, $groupId, $id = -1, $timeSent = null) {
		parent::__construct();

		$this->_id = $id;
		$this->_groupId = $groupId;
		$this->_type = $messageType;
		$this->_message = $message;
		$this->_timeSent = $timeSent;
	}

	protected function setModel() {
		if(!ENABLE_DATABASE_STORAGE)
			return;
		
		$this->_model = new MessageModel();
	}

	public function getMessageId() { return $this->_id; }
	public function getGroupId() { return $this->_groupId; }
	public function getMessage() { return $this->_message; }
	public function getTimeSent() { return $this->_timeSent; }
	public function getMessageType() { return $this->_type; }

	public function storeMessage() {
		if(!ENABLE_DATABASE_STORAGE)
			return;

		$data = array();
		$data["GroupId"] = $this->_groupId;
		$data["Message"] = $this->_message;
		$data["MessageType"] = $this->_type;

		$this->_id = $this->_model->createMessage($data);
	}

	public function updateTimeSent() {
		if(!ENABLE_DATABASE_STORAGE)
			return;

		$this->_timeSent = $this->_model->updateTimeStent($this->_id);
	}
}
