<?php namespace LuminoRay\RolePHP\Character;

interface iStatus {
	public function __construct(array $stat_array);
	public function stat_set($stat_prop, $value, $constraint, $compare, $fallback);
}