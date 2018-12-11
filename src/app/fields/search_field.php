<?php
/**
 * Field for a search input
 *
 * By default the field is not required and max_length is set to 200.
 */
class SearchField extends CharField{

	function __construct($options = array()){
		$options += array(
			"widget" => new SearchInput(),
			"required" => false,
			"max_length" => 200,
		);
		parent::__construct($options);
	}
}
