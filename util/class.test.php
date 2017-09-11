<?php

namespace japan;

class Test2 {
	public function __construct() {
		
	}

	public function g() {
		global $wpdb;
		
		$myrows = $wpdb->get_results( "
        SELECT * FROM wp_sample_table_zuke
        " );

        return $myrows;
	}



	public function f() {
		echo "hay!!";
	}
}
?>