<?php namespace LuminoRay\RolePHP\Character;

class Status implements iStatus {
	public function __construct(array $stat_array) {
		foreach ($stat_array as $stat => $value) {
			$this->stat_set($stat, $value);
		}
	}
	
	public function stat_set($stat_prop, $value, $constraint = 0, $compare = 'not_equal', $fallback = 'set_constraint') {
		$constraint_met = FALSE;
		switch ($compare) {
			case 'equal':
				$constraint_met = ($value === $constraint)?TRUE:FALSE;
				break;
			case 'not_equal':
				$constraint_met = ($value !== $constraint)?TRUE:FALSE;
				break;
			case 'less':
				$constraint_met = ($value < $constraint)?TRUE:FALSE;
				break;
			case 'less_equal':
				$constraint_met = ($value <= $constraint)?TRUE:FALSE;
				break;
			case 'greater':
				$constraint_met = ($value > $constraint)?TRUE:FALSE;
				break;
			case 'greater_equal':
				$constraint_met = ($value >= $constraint)?TRUE:FALSE;
				break;
			default:
				throw new \Exception ('Invalid constraint type.');
				break;
		}
		if ($constraint_met) {
			$this->$stat_prop = $value;
			return TRUE;
		} else {
			switch ($fallback) {
				case 'cancel':
					break;
				case 'set_constraint':
					$this->$stat_prop = $constraint;
					break;
				default:
					throw new \Exception ('Invalid fallback type.');
			}
			return FALSE;
		}
	}
}