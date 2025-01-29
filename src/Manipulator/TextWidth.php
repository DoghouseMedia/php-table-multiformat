<?php

namespace PHPTable\Manipulator;

// Text with limited width
class	TextWidth extends Base {
	protected	int	$max_width;
	protected	string	$wrapping;

	function __construct(
		string $type = 'textwidth',
		int $max_width = 40,
		string $wrapping = 'clip',
	) {
		$this->wrapping = $wrapping;
		$this->max_width = $max_width;
		parent::__construct($type);
	}

	public function textwidth($text) {
		if(strlen($text) > $this->max_width) {
			switch($this->wrapping) {
				case 'clip':
					return substr($text, 0, $this->max_width - 3) . '...' ;
				case 'wrap':
					$regex = '\s*(.{1,' . $this->max_width . '})(?=\s+|$)(.*)';
					$matches = [];
					$rows = [];
					while(preg_match("/$regex/", $text, $matches)) {
						array_push($rows, $matches[1]);
						$text = array_key_exists(2, $matches) ? $matches[2] : '';
					}
					return $rows;
				default:
					die("Error: Unknown action '{$this->action}'");
			}
		} else {
			return $text;
		}
	}
}
