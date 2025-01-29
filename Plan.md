Things Tim may or may not get around to, but would be considered beneficial

* Fix "Half-Human" to handle multi-line output a bit better (dashes in the empty columns, columns placed correctly); cf. HumanOnly for ideas
* Having done that, see if any of the common functionality can be merged into Base::getFormattedRow without disturbing CSV format
* Rewrite the Colour stuff to use https://github.com/bbatsche/ConsoleColor (so that we can do eg. background colours)
* Make \PHPTable\Format\HTML (which outputs the same table in HTML format; remember to support colours)
    * Should be able to support aligning left/centre/right as well
* JSON output, possibly based on https://github.com/jc21/clitable/compare/master...nexcess:jc21-clitable:master but I don't think that supports TableCell, so might need fixing
