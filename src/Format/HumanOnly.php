<?php
// +---------------------------------------------------------------+
// | Human Only Table Class                                        |
// +---------------------------------------------------------------+
// | Aesthetic output for PHP scripts on the command line          |
// +---------------------------------------------------------------+
// | Licence: MIT                                                  |
// +---------------------------------------------------------------+
// | Copyright:                                                    |
// |     Jamie Curnow  <jc@jc21.com>                               |
// |     Tim Nelson <tnelson@doghouse.agency>                      |
// +---------------------------------------------------------------+
//

namespace PHPTable\Format;

class HumanOnly extends Base {
    protected $useColors = false;

    /**
     * getTableTop
     *
     * @access protected
     * @param  array   $columnLengths
     * @return string
     */
    protected function getTableTop($columnLengths) {
        $response = $this->getChar('top-left');
        foreach ($columnLengths as $length) {
            $response .= $this->getChar('top', $length + 2);
            $response .= $this->getChar('top-mid');
        }
        $response = substr($response, 0, strlen($response) - 3) . $this->getChar('top-right') . PHP_EOL;
        return $response;
    }


    /**
     * getTableBottom
     *
     * @access protected
     * @param  array   $columnLengths
     * @return string
     */
    protected function getTableBottom($columnLengths) {
        $response = $this->getChar('bottom-left');
        foreach ($columnLengths as $length) {
            $response .= $this->getChar('bottom', $length + 2);
            $response .= $this->getChar('bottom-mid');
        }
        $response = substr($response, 0, strlen($response) - 3) . $this->getChar('bottom-right') . PHP_EOL;
        return $response;
    }


    /**
     * getTableSeparator
     *
     * @access protected
     * @param  array   $columnLengths
     * @return string
     */
    protected function getTableSeparator($columnLengths) {
        $response = $this->getChar('left-mid');
        foreach ($columnLengths as $length) {
            $response .= $this->getChar('mid', $length + 2);
            $response .= $this->getChar('mid-mid');
        }
        $response = substr($response, 0, strlen($response) - 3) . $this->getChar('right-mid') . PHP_EOL;
        return $response;
    }


    /**
     * getChar
     *
     * @access protected
     * @param  string  $type
     * @param  int     $length
     * @return string
     */
    protected function getChar($type, $length = 1) {
        $response = '';
        if (isset($this->chars[$type])) {
            if ($this->getUseColors()) {
                $response .= $this->getColorFromName($this->getTableColor());
            }
            $char = trim($this->chars[$type]);
            for ($x = 0; $x < $length; $x++) {
                $response .= $char;
            }
        }
        return $response;
    }


    /**
     * getColorFromName
     *
     * @access protected
     * @param  string  $colorName
     * @return string
     */
    protected function getColorFromName($colorName)
    {
        if (isset($this->colors[$colorName])) {
            return $this->colors[$colorName];
        }
        return $this->colors['reset'];
    }


	/**
	* get
	*
	* @access public
	* @return string
	*/
	public function get() {
		$rowCount	= 0;
		$columnLengths	= array();
		$headerData	= array();
		$cellData	= array();

		// Headers
		if ($this->getShowHeaders()) {
			foreach ($this->fields as $field) {
				$headerData[$field['key']] = trim($field['name']);

				// Column Lengths
				if (!isset($columnLengths[$field['key']])) {
					$columnLengths[$field['key']] = 0;
				}
				$columnLengths[$field['key']] = max($columnLengths[$field['key']], strlen(trim($field['name'])));
			}
		}

		// Data
		if ($this->injectedData === null) {
			return 'There is no injected data for the table!' . PHP_EOL;
		}
		if (! count($this->injectedData)) {
			return 'There are no '.$this->getPluralItemName() . PHP_EOL;
		}
		foreach ($this->injectedData as $row) {
			// Row
			if(is_scalar($row)) {
				 $cellData[$rowCount] = $row;
			} else {
				$cellData[$rowCount] = array();
				foreach ($this->fields as $field) {
					$key   = $field['key'];
					$cellObj = new \PHPTable\Cell($field, $row, $key);
					$cellData[$rowCount][$key] = $cellObj;

					// Column Lengths
					if (!isset($columnLengths[$key])) {
						$columnLengths[$key] = 0;
					}
					$columnLengths[$key] = max($columnLengths[$key], $cellObj->get_width());
				}
			}
			$rowCount++;
		}

		$response = '';

		// Now draw the table!
		$response .= $this->getTableTop($columnLengths);
		if ($this->getShowHeaders()) {
			$response .= $this->getFormattedRow($headerData, $columnLengths, true);
			$response .= $this->getTableSeparator($columnLengths);
		}

		foreach ($cellData as $row) {
			$response .= $this->getFormattedRow($row, $columnLengths);
		}

		$response .= $this->getTableBottom($columnLengths);

		return $response;
	}

	/**
	* getFormattedRow
	*
	* @access protected
	* @param  array   $rowData
	* @param  array   $columnLengths
	* @param  bool    $header
	* @return string
	*/
	protected function getFormattedRow($rowData, $columnLengths, $header = false) {
		if (is_scalar($rowData)) {
			if (preg_match("/^-+$/", $rowData)) {
				$response = $this->getTableSeparator($columnLengths); // Developer couldn't spell separator
			} else {
				warn("Warning: Unknown scalar row data: '$rowData'\n");
			}
		} else {
			$maxLines = 1;
			foreach ($rowData as $key => $field) {
				if($field instanceof \PHPTable\Cell) {
					$maxLines = max($maxLines, $field->get_lines());
				} else {
					$maxLines = max($maxLines, count(explode(PHP_EOL, $field)));
				}
			}
			$response = '';
			for($count = 1; $count <= $maxLines; $count++) {
				$response .= $this->getFormattedLine($rowData, $count, $columnLengths, $header);
			}
		}
		return $response;
	}

	// Same as the above, but does only one line (when wrapping is involved) -- with $count
	protected function getFormattedLine($rowData, $count, $columnLengths, $header = false) {
		$response = $this->getChar('left');

		foreach ($rowData as $key => $field) {
			$color = NULL;
			$fieldValue = NULL;
			if($field instanceof \PHPTable\Cell) {
				$color = $field->color;
				$fieldValue = array_key_exists($count-1, $field->rows) ? $field->rows[$count-1] : '';
			} else {
				$fieldValue = $field;
			}
			if(empty($color)) {
				if ($header) {
					$color = $this->getHeaderColor();
				} else {
					$color = $this->fields[$key]['color'];
				}
			}
			if($fieldValue == NULL) { $fieldValue = ''; }

			$fieldLength  = mb_strwidth($fieldValue) + 1;
			$fieldValue   = ' '.($this->getUseColors() ? $this->getColorFromName($color) : '').$fieldValue;
			$response    .= $fieldValue;

			for ($x = $fieldLength; $x < ($columnLengths[$key] + 2); $x++) {
				$response .= ' ';
			}
			$response .= $this->getChar('middle');
		}

		$response = substr($response, 0, strlen($response) - 3) . $this->getChar('right') . PHP_EOL;
		return $response;
	}

	protected function defineColors() {
		parent::defineColors();
		/*
			First number:
				38: Foreground
				48: Background
			Second number:
				If 5, use 256-color palette
			Third number:
				Select the actual color
		*/
	        $this->colors['orange'] = chr(27).'[38;5;166m';
	}
}
