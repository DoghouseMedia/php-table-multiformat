<?php

namespace PHPTable;

class	Cell {
	public		$rows = [];
	public		$color = NULL;

	function	__construct($field, $row, string $key) {
		$input = array_key_exists($key, $row) ? $row[$key] : '';
		if(is_scalar($input) or $input == NULL) {
			$value = $input;
			$this->color = NULL;
		} else {
			$value = $input['value'];
			$this->color = $input['color'];
		}
		if ($field['manipulator'] instanceof \PHPTable\Manipulator\Base) {
			$rv = $field['manipulator']->manipulate($value, $row, $field['name']);
			$rv = is_scalar($rv) ? [$rv] : $rv;
			$this->rows = array_map(
				function ($item) { return trim($item); },
				$rv
			);
		} else {
			$lines = explode(PHP_EOL, $value == NULL ? '' : $value);
			array_push($this->rows, ...$lines);
		}
	}

	function	get_width() {
		return array_reduce($this->rows, function ($carry, $item) {
			return max($carry, $item == NULL ? 0 : strlen($item));
		});
	}

	function	get_lines() {
		return count($this->rows);
	}

	function	__toString() {
		return implode(PHP_EOL, $this->rows);
	}
}
