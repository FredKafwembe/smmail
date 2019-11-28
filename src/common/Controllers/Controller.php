<?php
/**
 * Base abstract class for all controllers
 */
abstract class Controller {
	protected $_model;

	function __construct() {
		$this->setModel();
	}

	abstract protected function setModel();
}