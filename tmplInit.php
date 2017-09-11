<?php

require_once plugin_dir_path( __FILE__ ).'Twig/Autoloader.php';

class TwigInit
{

	public static function init() {
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem(__DIR__ . "/tmpl");
		$Twig = new Twig_Environment($loader,array('debug' => true));
		$Twig->addExtension(new Twig_Extension_Debug());
		return $Twig;
	}
}