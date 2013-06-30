<?php

define( 'SHOW_DEBUG', false ) ;
define( 'APPLICATION_BASE_DIR', '/path/to/miragevc' ) ;
define( 'APPLICATION_BASE_URI', '' ) ;
define( 'APPLICATION_BASE_HREF', 'http://mydomain.com'.APPLICATION_BASE_URI.'/' ) ;
define( 'APPLICATION_DSN', '' ) ;
define( 'APPLICATION_SESSION_NAME', 'ILOVEOREOS' ) ;
//define('Auth_OpenID_RAND_SOURCE', null);

ini_set( 'include_path', "/path/to/php:" . ini_get( 'include_path' )) ;
ini_set( 'include_path', "/path/to/php/PEAR-1.6.2:" . ini_get( 'include_path' )) ;

require_once( "PEAR.php" ) ;

?>
