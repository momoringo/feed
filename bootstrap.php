<?php

require_once plugin_dir_path(__FILE__)."core/AutoLoader.php";

$autoLoader = new AutoLoader();

$autoLoader->registerDir(dirname(__FILE__). 'core');

$autoLoader->register();