<?php
/**
* 
*/

namespace Session;

class SessionService
{
	
	function __construct()
	{
      add_action('init', [$this,'init']);

	}

	public static function init() {
		session_start();
	}

	public function setSession($prop,$param) {
		$_SESSION[$prop] = $param;
	}

	public function getSession($prop = null) {

		if(!isset($prop)) {
			return $_SESSION;
		}
		return $_SESSION[$prop];
	}

}