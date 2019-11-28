<?php

class Group extends Controller {
	private $_groupId = -1;
	private $_groupName;
	private $_emailGroup;
	private $_smsGroup;
	private $_newContacts;
	private $_groupContacts;

	/**
	 * @param String $groupName The name of the group
	 * @param Bool $isSmsGroup If true group will support SMS functionality.
	 * @param Bool $isEmailGroup If true group will support email functionality.
	 * @param Bool $createGroup If true a new group will be created with the given name. If false 
	 * the group is created from stored information using the group name specified.
	 * 
	 * @throws Exception When insertion of new group into database fails.
	 * @throws Exception If attempting to create a new group and the given group name already exists.
	 * @throws Exception If the given group name's information is not found and not attempting to create a new group.
	 */
	function __construct($groupName, $isSmsGroup, $isEmailGroup) { //, $createGroup = false) {
		parent::__construct();

		$this->_groupName = $groupName;
		$this->_emailGroup = $isEmailGroup;
		$this->_smsGroup = $isSmsGroup;
	}

	protected function setModel() {
		if(!ENABLE_DATABASE_STORAGE)
			return;

		$this->_model = new GroupModel();
	}

	/**
	 * @return String The name of the group
	 */
	public function getName() { return $this->_groupName; }
	/**
	 * @return Integer The unique id of the group
	 */
	public function getId() { return $this->_groupId; }
	public function isEmailGroup() { return $this->_emailGroup; }
	public function isSmsGroup() { return $this->_smsGroup; }

	public function addContactToGroup($contact) {
		if(ENABLE_DATABASE_STORAGE) {
			$this->_newContacts[] = $contact;
		} else {
			$this->_groupContacts[] = $contact;
		}
	}

	public function getGroupContacts() { return $this->_groupContacts; }

	public function sendMessage($message) {
		if(ENABLE_DATABASE_STORAGE) {
			$message->storeMessage();
			$message->updateTimeSent();
		}
		
		foreach($this->_groupContacts as $contact) {

			/*******Insert message sending code here**********/

			if(ENABLE_DATABASE_STORAGE) {
				$messageInfo = array(
					"MessageId" => $message->getMessageId(),
					"ContactId" => $contact->getId(),
					"IsSent" => true	/********Message sending status********/
				);
				$this->_model->storeMessageInformation($messageInfo);
			}
		}
	}

	public function storeGroup() {
		if(!ENABLE_DATABASE_STORAGE)
			return;

		$data = array(
			"GroupName" => $this->_groupName,
			"IsEmailGroup" => $this->_emailGroup,
			"IsSmsGroup" => $this->_smsGroup
		);
		$this->_groupId = $this->_model->createGroup($data);
	}

	public function storeGroupContacts() {
		if(!ENABLE_DATABASE_STORAGE)
			return;

		if($this->_groupId == -1)
			throw new Exception("Group has not been stored or loaded yet");

		while(count($this->_newContacts) > 0) {
			$contact = array_shift($this->_newContacts);
			$data = array(
				"ContactId" => $contact->getId(),
				"ContactName" => $contact->getName(),
				"GroupId" => $this->_groupId,
				"GroupName" => $this->_groupName
			);
			$this->_model->addContactToGroup($data);
			$this->_groupContacts[] = $contact;
		}
	}

	public function loadGroupInfoFromStorage() {
		if(!ENABLE_DATABASE_STORAGE)
			return;

		$groupInfo = $this->_model->findGroup($this->_groupName);

		if(!$groupInfo) {
			throw new Exception("Failed to find '$this->_groupName'");
		}

		$this->_groupId = $groupInfo["GroupId"];
		$this->_emailGroup = ($groupInfo["IsEmailGroup"] == 1) ? true : false;
		$this->_smsGroup = ($groupInfo["IsSmsGroup"] == 1) ? true : false;

		$this->loadGroupContacts();
	}

	private function loadGroupContacts() {
		//print("Need to implement contact loading");
		$this->_groupContacts = $this->_model->loadGroupContacts($this->_groupId);
		//$this->_groupContacts = $con
		//print_r($contacts);
	}
}
