<?php

function rb_interactivemapRandom() {
	return preg_replace("/([0-9])/e","chr((\\1+112))",rand(100000,999999));
} 

function rb_interactivemapWhiteSpace($string) {
	return preg_replace('/\s+/', ' ', $string);
}

// Format a string in proper case.
function rb_interactivemapProper($someString) {
	return ucwords(strtolower($someString));
}

?>