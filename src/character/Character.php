<?php namespace LuminoRay\RolePHP\Character;

abstract class Character implements iCharacter {
	public function __construct(iStatus $status) {
		$this->status = $status;
	}
	
	public function __get($stat_prop) {
		return $this->status->$stat_prop;
	}
}