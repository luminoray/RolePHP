<?php namespace LuminoRay\RolePHP\Character;

interface iCharacter {
	public function __construct(iStatus $status);
	public function attack(iCharacter $target);
	public function skill($name, $level, iCharacter $target);
	public function __get($stat_prop);
}