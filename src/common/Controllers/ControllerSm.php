<?php
/**
 * Base abstract class for all controllers
 */
abstract class ControllerSm {
	protected $_model;

	function __construct() {
		$this->setModel();
	}

	abstract protected function setModel();
}