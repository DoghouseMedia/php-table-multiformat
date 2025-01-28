<?php

// Output that's both human readable and machine readable, like many Unix commands

namespace PHPTable\Format;

// Half-human format -- tabular, but with no separating lines, and headers have spaces turned to '_'.  Unix-like.  
class	HalfHumanCliTable extends GenericTable {
	protected function getFormattedRow($rowData, $columnLengths, $header = false) {
		$response = '';
		foreach ($rowData as $key => $field) {
			$fieldValue = $field instanceof Cell ? implode(' ', $field->rows) : $field;

			// If it's a header, turn whitespace into underscores
			if($header) {
				$fieldValue = preg_replace("/\s/", '_', $fieldValue);
			}
			$fieldLength  = mb_strwidth($fieldValue) + 1;
			$response    .= $fieldValue;

			$response .= str_repeat(' ', ($columnLengths[$key] + 2) - $fieldLength);
		}

		$response = $response . PHP_EOL;

		return $response;
	}
}
?>