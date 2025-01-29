<?php

namespace PHPTable\Format;

// CSV format
class	CSVTable extends Base {
	protected function getFormattedRow($rowData, $columnLengths, $header = false) {
		$response = implode(',', array_map(function ($item) {
			$fieldValue = $item instanceof Cell ? implode(' ', $item->rows) : $item;
			return '"' . $fieldValue . '"';
		}, $rowData)) . PHP_EOL;

		return $response;
	}
}