<?php

class Test {
     function activate(){


     	$this -> oxy_hello_world2();

     }


	public function oxy_hello_world2() {



		add_action( 'admin_init', array( $this, 'oxy_hello_world'));


	     
	}

	public function oxy_hello_world() {

//$this -> tes = 999;
	     add_menu_page( 'hoge','hoge','manage_options','myplugin_setting' );
	}




}
?>