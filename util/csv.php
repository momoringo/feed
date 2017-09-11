//var_dump(WP_PLUGIN_URL."/zuke/c.csv");
//$file = new SplFileObject(WP_PLUGIN_URL."/zuke/c.csv");

$url  = WP_PLUGIN_URL."/zuke/c.csv"; 

$file = new NoRewindIterator( new SplFileObject( $url ));

$file->setFlags( SplFileObject::READ_CSV );

foreach ( $file as $line ) {

    $results[] = $line;

}

var_dump( $results );