<?php
class Test extends CComponent {
	
	private $read = 'Read Only';
	private $write = 'Write Only';
	
	public function getRead() {
		return $this->read;
	}
	
	public function setWrite($value) {
		$this->write = $value;
	}
	
}