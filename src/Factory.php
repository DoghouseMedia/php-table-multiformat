<?php

namespace PHPTable;

class	Factory {
	// Makes a Table object of an appropriate type for the given format
	// Keep in sync with the function immediately following
	public static function	make(string $format = 'human-only') {
		switch($format) {
			case 'human-only':
				$vtable = new \PHPTable\Format\HumanOnly;
				break;
			case 'half-human':
				$vtable = new \PHPTable\Format\HalfHuman;
				break;
			case 'csv':
				$vtable = new \PHPTable\Format\CSVTable;
				break;
			default:
				die("Error: Unrecognised format: {$format}\n");
		}

		return $vtable;
	}
	// Keep in sync with the immediately preceding function
	function	listFormats() {
		return ['human-only', 'half-human', 'csv'];
	}
}
